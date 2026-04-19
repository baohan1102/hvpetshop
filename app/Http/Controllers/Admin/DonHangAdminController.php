<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\LichSuDonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonHangAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = DonHang::with(['user', 'chiTiets'])->latest();

        if ($request->trang_thai) $query->where('trang_thai', $request->trang_thai);
        if ($request->tu_khoa) {
            $query->where(fn($q) => $q->where('ma_dh', 'like', '%'.$request->tu_khoa.'%')
                ->orWhere('ho_ten_nhan', 'like', '%'.$request->tu_khoa.'%')
                ->orWhere('so_dien_thoai_nhan', 'like', '%'.$request->tu_khoa.'%'));
        }
        if ($request->ngay_tu) $query->whereDate('ngay_dat', '>=', $request->ngay_tu);
        if ($request->ngay_den) $query->whereDate('ngay_dat', '<=', $request->ngay_den);

        $donHangs = $query->paginate(15)->appends($request->all());
        return view('admin.don-hang.index', compact('donHangs'));
    }

    public function show($id)
    {
        $donHang = DonHang::with(['user', 'chiTiets.sanPham', 'khuyenMai', 'lichSus.nguoiThucHien', 'nhanVien'])->findOrFail($id);
        return view('admin.don-hang.show', compact('donHang'));
    }

    public function capNhatTrangThai(Request $request, $id)
    {
        $request->validate(['trang_thai' => 'required|in:da_xac_nhan,dang_giao,da_hoan_thanh,da_huy']);
        $donHang = DonHang::findOrFail($id);

        DB::transaction(function () use ($donHang, $request) {
            $data = ['trang_thai' => $request->trang_thai, 'nhan_vien_id' => Auth::id()];

            if ($request->trang_thai === 'dang_giao') {
                $data['ngay_giao_du_kien'] = now()->addDays(3);
            }
            if ($request->trang_thai === 'da_hoan_thanh') {
                $data['ngay_giao_thuc_te'] = now();
                $data['da_nhan_hang'] = true;
            }
            if ($request->trang_thai === 'da_huy') {
                // Hoàn kho
                foreach ($donHang->chiTiets()->with('sanPham')->get() as $ct) {
                    $ct->sanPham->increment('so_luong', $ct->so_luong);
                }
                if ($donHang->khuyen_mai_id) {
                    $donHang->khuyenMai?->decrement('so_lan_da_dung');
                }
            }

            $donHang->update($data);
            LichSuDonHang::create([
                'don_hang_id'   => $donHang->id,
                'trang_thai'    => $request->trang_thai,
                'thuc_hien_boi' => Auth::id(),
            ]);
        });

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}