<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $danhMucs = DanhMuc::active()->withCount(['sanPhams' => fn($q) => $q->active()])->get();
        $sanPhamsMoi = SanPham::active()->where('la_moi', true)->latest()->take(8)->get();
        $sanPhamBanChay = SanPham::active()
            ->withCount('chiTietDonHangs')
            ->orderByDesc('chi_tiet_don_hangs_count')
            ->take(8)->get();
        return view('home', compact('danhMucs', 'sanPhamsMoi', 'sanPhamBanChay'));
    }

    public function danhSachSanPham(Request $request)
    {
        $query = SanPham::active()->with('danhMuc');

        if ($request->danh_muc) {
            $query->where('danh_muc_id', $request->danh_muc);
        }
        if ($request->tu_khoa) {
            $query->where('ten_sp', 'like', '%' . $request->tu_khoa . '%');
        }
        if ($request->gia_tu) {
            $query->where('gia', '>=', $request->gia_tu);
        }
        if ($request->gia_den) {
            $query->where('gia', '<=', $request->gia_den);
        }
        if ($request->khoang_gia) {
            switch ($request->khoang_gia) {
                case 'duoi100':   $query->where('gia', '<', 100000); break;
                case '100-500':   $query->whereBetween('gia', [100000, 500000]); break;
                case 'tren500':   $query->where('gia', '>', 500000); break;
            }
        }

        $sapXep = $request->sap_xep ?? 'moi_nhat';
        match ($sapXep) {
            'gia_tang'  => $query->orderBy('gia'),
            'gia_giam'  => $query->orderByDesc('gia'),
            'ban_chay'  => $query->withCount('chiTietDonHangs')->orderByDesc('chi_tiet_don_hangs_count'),
            default     => $query->latest(),
        };

        $sanPhams = $query->paginate(12)->appends($request->all());
        $danhMucs = DanhMuc::active()->get();

        return view('san-pham.danh-sach', compact('sanPhams', 'danhMucs'));
    }

    public function chiTietSanPham($id)
    {
        $sanPham = SanPham::with(['danhMuc', 'danhGias.user'])->active()->findOrFail($id);
        $sanPhamLienQuan = SanPham::active()
            ->where('danh_muc_id', $sanPham->danh_muc_id)
            ->where('id', '!=', $id)
            ->take(4)->get();

        $danhGias = $sanPham->danhGias()->where('da_duyet', true)->with('user')->latest()->get();

        // Thống kê đánh giá
        $thongKeDanhGia = [];
        for ($i = 5; $i >= 1; $i--) {
            $thongKeDanhGia[$i] = $danhGias->where('so_sao', $i)->count();
        }

        return view('san-pham.chi-tiet', compact('sanPham', 'sanPhamLienQuan', 'danhGias', 'thongKeDanhGia'));
    }

    public function timKiem(Request $request)
    {
        $tuKhoa = $request->q;
        $sanPhams = SanPham::active()
            ->where('ten_sp', 'like', "%$tuKhoa%")
            ->orWhere('mo_ta', 'like', "%$tuKhoa%")
            ->paginate(12);
        return view('san-pham.tim-kiem', compact('sanPhams', 'tuKhoa'));
    }
}