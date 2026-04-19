<?php

namespace App\Http\Controllers;

use App\Models\GioHang;
use App\Models\SanPham;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GioHangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $gioHangs = GioHang::where('user_id', Auth::id())
            ->with('sanPham.danhMuc')
            ->get();

        $tongTien = $gioHangs->sum(fn($item) => $item->so_luong * $item->gia);
        $phiShip = 35000;
        $khuyenMais = KhuyenMai::where('trang_thai', true)
            ->where('ngay_bat_dau', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->get()
            ->filter(fn($km) => $km->isConHieuLuc());

        return view('gio-hang.index', compact('gioHangs', 'tongTien', 'phiShip', 'khuyenMais'));
    }

    public function them(Request $request, $sanPhamId)
    {
        $sanPham = SanPham::active()->findOrFail($sanPhamId);
        $soLuong = max(1, intval($request->so_luong ?? 1));

        if ($soLuong > $sanPham->so_luong) {
            if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Không đủ hàng trong kho!']);
            return back()->withErrors(['Không đủ hàng trong kho!']);
        }

        $item = GioHang::where('user_id', Auth::id())->where('san_pham_id', $sanPhamId)->first();

        if ($item) {
            $newQty = $item->so_luong + $soLuong;
            if ($newQty > $sanPham->so_luong) $newQty = $sanPham->so_luong;
            $item->update(['so_luong' => $newQty, 'gia' => $sanPham->gia]);
        } else {
            GioHang::create([
                'user_id' => Auth::id(),
                'san_pham_id' => $sanPhamId,
                'so_luong' => $soLuong,
                'gia' => $sanPham->gia,
            ]);
        }

        $count = GioHang::where('user_id', Auth::id())->count();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Đã thêm vào giỏ hàng!', 'count' => $count]);
        }
        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function capNhatSoLuong(Request $request, $id)
    {
        $item = GioHang::where('user_id', Auth::id())->findOrFail($id);
        $soLuong = max(1, intval($request->so_luong));

        if ($soLuong > $item->sanPham->so_luong) {
            $soLuong = $item->sanPham->so_luong;
        }

        $item->update(['so_luong' => $soLuong]);

        $thanhTien = $item->so_luong * $item->gia;
        $tongGioHang = GioHang::where('user_id', Auth::id())->get()->sum(fn($i) => $i->so_luong * $i->gia);

        return response()->json([
            'success' => true,
            'thanh_tien' => $thanhTien,
            'thanh_tien_format' => number_format($thanhTien) . 'đ',
            'tong_gio_hang' => $tongGioHang,
            'tong_gio_hang_format' => number_format($tongGioHang) . 'đ',
        ]);
    }

    public function xoa($id)
    {
        GioHang::where('user_id', Auth::id())->where('id', $id)->delete();
        $count = GioHang::where('user_id', Auth::id())->count();
        return response()->json(['success' => true, 'count' => $count]);
    }

    public function xoaTatCa()
    {
        GioHang::where('user_id', Auth::id())->delete();
        return redirect()->route('gio-hang')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    public function apDungKhuyenMai(Request $request)
    {
        $maKm = strtoupper(trim($request->ma_km));
        $tongTien = floatval($request->tong_tien);

        $km = KhuyenMai::where('ma_km', $maKm)->first();

        if (!$km || !$km->isConHieuLuc()) {
            return response()->json(['success' => false, 'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn!']);
        }

        if ($tongTien < $km->don_hang_toi_thieu) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($km->don_hang_toi_thieu) . 'đ để áp dụng mã này!'
            ]);
        }

        // Kiểm tra giới hạn mỗi KH
        if ($km->gioi_han_moi_kh) {
            $daDs = \App\Models\KhuyenMaiSuDung::where('user_id', Auth::id())
                ->where('khuyen_mai_id', $km->id)->count();
            if ($daDs >= $km->gioi_han_moi_kh) {
                return response()->json(['success' => false, 'message' => 'Bạn đã dùng hết lượt cho mã này!']);
            }
        }

        $result = $km->tinhGiam($tongTien);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã thành công!',
            'khuyen_mai_id' => $km->id,
            'ten' => $km->ten_chuong_trinh,
            'giam' => $result['giam'],
            'giam_format' => number_format($result['giam']) . 'đ',
            'phi_ship_moi' => $result['phi_ship_moi'],
            'phi_ship_moi_format' => $result['phi_ship_moi'] > 0 ? number_format($result['phi_ship_moi']) . 'đ' : 'Miễn phí',
            'loai' => $km->loai,
        ]);
    }

    public function demGioHang()
    {
        $count = Auth::check() ? GioHang::where('user_id', Auth::id())->count() : 0;
        return response()->json(['count' => $count]);
    }
    public function prepareCheckout(Request $request)
{
    $selectedIds = $request->selected_ids ?? [];

    if (empty($selectedIds)) {
        return response()->json([
            'success' => false,
            'message' => 'Chưa chọn sản phẩm'
        ]);
    }

    // Lưu session để qua trang checkout dùng
    session([
        'checkout_items' => $selectedIds,
        'khuyen_mai_id' => $request->khuyen_mai_id
    ]);

    return response()->json([
        'success' => true
    ]);
}
}