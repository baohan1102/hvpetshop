<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\DonHang;
use App\Models\User;
use App\Models\DanhMuc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tongSanPham  = SanPham::where('trang_thai', true)->count();
        $tongDanhMuc  = DanhMuc::where('trang_thai', true)->count();
        $tongDonHang  = DonHang::count();
        $tongNhanVien = User::where('vai_tro', 'nhan_vien')->count();
        $tongKhachHang= User::where('vai_tro', 'khach_hang')->count();

        $doanhThuThang = DonHang::where('trang_thai', 'da_hoan_thanh')
            ->whereMonth('ngay_dat', now()->month)
            ->whereYear('ngay_dat', now()->year)
            ->sum('thanh_tien');

        $donHangMoi = DonHang::where('trang_thai', 'cho_xac_nhan')->count();

        // Biểu đồ doanh thu 6 tháng gần nhất
        $doanhThu6Thang = [];
        for ($i = 5; $i >= 0; $i--) {
            $thang = Carbon::now()->subMonths($i);
            $doanhThu6Thang[] = [
                'thang' => $thang->format('m/Y'),
                'doanh_thu' => DonHang::where('trang_thai', 'da_hoan_thanh')
                    ->whereMonth('ngay_dat', $thang->month)
                    ->whereYear('ngay_dat', $thang->year)
                    ->sum('thanh_tien'),
            ];
        }

        $sanPhamBanChay = SanPham::withCount(['chiTietDonHangs as da_ban' => fn($q) =>
            $q->whereHas('donHang', fn($q2) => $q2->whereIn('trang_thai', ['da_hoan_thanh', 'dang_giao']))
        ])->orderByDesc('da_ban')->take(5)->get();

        $donHangsGanDay = DonHang::with('user')->latest()->take(10)->get();
        $sanPhamHetHang = SanPham::where('trang_thai', true)->where('so_luong', '<=', DB::raw('nguong_canh_bao'))->get();

        return view('admin.dashboard', compact(
            'tongSanPham', 'tongDanhMuc', 'tongDonHang', 'tongNhanVien',
            'tongKhachHang', 'doanhThuThang', 'donHangMoi', 'doanhThu6Thang',
            'sanPhamBanChay', 'donHangsGanDay', 'sanPhamHetHang'
        ));
    }
}