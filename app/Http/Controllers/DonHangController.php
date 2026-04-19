<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\GioHang;
use App\Models\SanPham;
use App\Models\KhuyenMai;
use App\Models\KhuyenMaiSuDung;
use App\Models\LichSuDonHang;
use App\Models\DanhGiaSanPham;
use App\Models\DiaChi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonHangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout(Request $request)
    {
        // Lấy các item được chọn (từ checkbox giỏ hàng)
        $selectedIds = $request->session()->get('selected_cart_ids', []);

        $gioHangs = GioHang::where('user_id', Auth::id())
            ->when(!empty($selectedIds), fn($q) => $q->whereIn('id', $selectedIds))
            ->with('sanPham')
            ->get();

        if ($gioHangs->isEmpty()) {
            return redirect()->route('gio-hang')->withErrors(['Vui lòng chọn sản phẩm cần mua!']);
        }

        $tongTien = $gioHangs->sum(fn($i) => $i->so_luong * $i->gia);
        $diaChis = DiaChi::where('user_id', Auth::id())->get();
        $diaChiMacDinh = $diaChis->where('la_mac_dinh', true)->first();
        $phiShip = 35000;

        return view('don-hang.checkout', compact('gioHangs', 'tongTien', 'diaChis', 'diaChiMacDinh', 'phiShip'));
    }

    public function prepareCheckout(Request $request)
    {
        $selectedIds = $request->selected_ids ?? [];
        $request->session()->put('selected_cart_ids', $selectedIds);
        return response()->json(['success' => true]);
    }

    public function datHang(Request $request)
    {
        $request->validate([
            'ho_ten_nhan'       => 'required',
            'so_dien_thoai_nhan'=> 'required|regex:/^[0-9]{10,11}$/',
            'dia_chi_giao'      => 'required',
            'phuong_thuc_tt' => 'required|in:chuyen_khoan,cod,momo,visa,vnpay',
        ]);

        $selectedIds = $request->session()->get('selected_cart_ids', []);
        $gioHangs = GioHang::where('user_id', Auth::id())
            ->when(!empty($selectedIds), fn($q) => $q->whereIn('id', $selectedIds))
            ->with('sanPham')->get();

        if ($gioHangs->isEmpty()) {
            return redirect()->route('gio-hang')->withErrors(['Giỏ hàng trống!']);
        }

        // Kiểm tra tồn kho
        foreach ($gioHangs as $item) {
            if ($item->sanPham->so_luong < $item->so_luong) {
                return back()->withErrors(["Sản phẩm '{$item->sanPham->ten_sp}' không đủ số lượng!"]);
            }
        }

        DB::transaction(function () use ($request, $gioHangs) {
            $tongTien = $gioHangs->sum(fn($i) => $i->so_luong * $i->gia);
            $phiShip = 35000;
            $tienGiam = 0;

            // Áp dụng khuyến mãi
            $khuyenMaiId = null;
            if ($request->khuyen_mai_id) {
                $km = KhuyenMai::find($request->khuyen_mai_id);
                if ($km && $km->isConHieuLuc()) {
                    $result = $km->tinhGiam($tongTien, $phiShip);
                    $tienGiam = $result['giam'];
                    $phiShip = $result['phi_ship_moi'];
                    $khuyenMaiId = $km->id;
                    $km->increment('so_lan_da_dung');
                }
            }

            $thanhTien = $tongTien + $phiShip - $tienGiam;
            $maDH = 'DH' . strtoupper(uniqid());
            $ngayDat = Carbon::now();
            $ngayGiaoDuKien = Carbon::now()->addDays(3);

            $donHang = DonHang::create([
                'ma_dh'              => $maDH,
                'user_id'            => Auth::id(),
                'khuyen_mai_id'      => $khuyenMaiId,
                'tong_tien'          => $tongTien,
                'phi_van_chuyen'     => $phiShip,
                'tien_giam'          => $tienGiam,
                'thanh_tien'         => $thanhTien,
                'dia_chi_giao'       => $request->dia_chi_giao,
                'so_dien_thoai_nhan' => $request->so_dien_thoai_nhan,
                'ho_ten_nhan'        => $request->ho_ten_nhan,
                'phuong_thuc_tt'     => $request->phuong_thuc_tt,
                'trang_thai'         => 'cho_xac_nhan',
                'ngay_dat'           => $ngayDat,
                'ngay_giao_du_kien'  => $ngayGiaoDuKien,
            ]);

            // Tạo chi tiết & trừ kho
            foreach ($gioHangs as $item) {
                ChiTietDonHang::create([
                    'don_hang_id' => $donHang->id,
                    'san_pham_id' => $item->san_pham_id,
                    'so_luong'    => $item->so_luong,
                    'gia'         => $item->gia,
                    'thanh_tien'  => $item->so_luong * $item->gia,
                ]);
                $item->sanPham->decrement('so_luong', $item->so_luong);
            }

            // Lịch sử đơn hàng
            LichSuDonHang::create([
                'don_hang_id'   => $donHang->id,
                'trang_thai'    => 'cho_xac_nhan',
                'thuc_hien_boi' => Auth::id(),
            ]);

            // Ghi nhận sử dụng KM
            if ($khuyenMaiId) {
                KhuyenMaiSuDung::create([
                    'user_id'       => Auth::id(),
                    'khuyen_mai_id' => $khuyenMaiId,
                    'don_hang_id'   => $donHang->id,
                ]);
            }

            // Xóa các item đã checkout khỏi giỏ
            $ids = $gioHangs->pluck('id');
            GioHang::whereIn('id', $ids)->delete();

            session()->forget('selected_cart_ids');
            session()->put('last_order_id', $donHang->id);
        });

$donHangId = session('last_order_id');
$donHang   = \App\Models\DonHang::find($donHangId);

if ($donHang && $donHang->phuong_thuc_tt === 'vnpay') {
    return redirect()->route('vnpay.payment', $donHangId);
}

return redirect()->route('don-hang.xac-nhan', $donHangId);    }

    public function xacNhan($id)
    {
        $donHang = DonHang::with(['chiTiets.sanPham', 'khuyenMai'])->findOrFail($id);
        if ($donHang->user_id !== Auth::id()) abort(403);
        return view('don-hang.xac-nhan', compact('donHang'));
    }

    public function lichSu(Request $request)
    {
        $query = DonHang::where('user_id', Auth::id())->with(['chiTiets.sanPham', 'khuyenMai'])->latest();

        if ($request->trang_thai) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $donHangs = $query->paginate(10);
        return view('don-hang.lich-su', compact('donHangs'));
    }

    public function chiTiet($id)
    {
        $donHang = DonHang::with(['chiTiets.sanPham', 'khuyenMai', 'lichSus'])->findOrFail($id);
        if ($donHang->user_id !== Auth::id()) abort(403);
        return view('don-hang.chi-tiet', compact('donHang'));
    }

    public function huy(Request $request, $id)
    {
        $request->validate(['ly_do' => 'required'], ['ly_do.required' => 'Vui lòng chọn lý do hủy']);

        $donHang = DonHang::with('chiTiets.sanPham')->findOrFail($id);
        if ($donHang->user_id !== Auth::id()) abort(403);
        if (!$donHang->canHuy()) return back()->withErrors(['Không thể hủy đơn hàng này!']);

        DB::transaction(function () use ($donHang, $request) {
            $donHang->update(['trang_thai' => 'da_huy', 'ly_do_huy' => $request->ly_do]);

            // Hoàn lại số lượng kho
            foreach ($donHang->chiTiets as $ct) {
                $ct->sanPham->increment('so_luong', $ct->so_luong);
            }

            LichSuDonHang::create([
                'don_hang_id'   => $donHang->id,
                'trang_thai'    => 'da_huy',
                'ly_do_huy'     => $request->ly_do,
                'thuc_hien_boi' => Auth::id(),
            ]);

            // Hoàn lại lượt KM
            if ($donHang->khuyen_mai_id) {
                $donHang->khuyenMai?->decrement('so_lan_da_dung');
                KhuyenMaiSuDung::where('don_hang_id', $donHang->id)->delete();
            }
        });

        return redirect()->route('don-hang.lich-su')->with('success', 'Đã hủy đơn hàng!');
    }

    public function nhanHang($id)
    {
        $donHang = DonHang::findOrFail($id);
        if ($donHang->user_id !== Auth::id()) abort(403);
        if (!$donHang->canNhanHang()) return back()->withErrors(['Không thể xác nhận nhận hàng!']);

        DB::transaction(function () use ($donHang) {
            $donHang->update([
                'da_nhan_hang'      => true,
                'trang_thai'        => 'da_hoan_thanh',
                'ngay_giao_thuc_te' => now(),
            ]);
            LichSuDonHang::create([
                'don_hang_id'   => $donHang->id,
                'trang_thai'    => 'da_hoan_thanh',
                'thuc_hien_boi' => Auth::id(),
            ]);
        });

        return redirect()->route('don-hang.chi-tiet', $id)->with('success', 'Đã xác nhận nhận hàng! Cảm ơn bạn đã mua sắm.');
    }

    public function danhGia(Request $request, $donHangId)
    {
        $request->validate([
            'san_pham_id' => 'required|exists:san_phams,id',
            'so_sao'      => 'required|integer|between:1,5',
            'nhan_xet'    => 'nullable|max:1000',
            'hinh_anh'    => 'nullable|image|max:2048',
        ]);

        $donHang = DonHang::findOrFail($donHangId);
        if ($donHang->user_id !== Auth::id()) abort(403);
        if (!$donHang->canDanhGia()) return back()->withErrors(['Chỉ có thể đánh giá đơn hàng đã hoàn thành!']);

        // Kiểm tra đã đánh giá chưa
        $daCoReview = DanhGiaSanPham::where('user_id', Auth::id())
            ->where('san_pham_id', $request->san_pham_id)
            ->where('don_hang_id', $donHangId)
            ->exists();
        if ($daCoReview) return back()->withErrors(['Bạn đã đánh giá sản phẩm này rồi!']);

        $hinhAnhPath = null;
        if ($request->hasFile('hinh_anh')) {
            $hinhAnhPath = $request->file('hinh_anh')->store('reviews', 'public');
        }

        DanhGiaSanPham::create([
            'user_id'    => Auth::id(),
            'san_pham_id'=> $request->san_pham_id,
            'don_hang_id'=> $donHangId,
            'so_sao'     => $request->so_sao,
            'nhan_xet'   => $request->nhan_xet,
            'hinh_anh'   => $hinhAnhPath,
            'da_duyet'   => true,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}