<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\SanPham;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BaoCaoController extends Controller
{
    public function index(Request $request) {
        $kieu = $request->kieu ?? 'thang';
        $now = Carbon::now();
        $query = DonHang::where('trang_thai', 'da_hoan_thanh');
        $labels = []; $data = [];

        if ($kieu === 'ngay') {
            for ($i = 6; $i >= 0; $i--) {
                $ngay = $now->copy()->subDays($i);
                $labels[] = $ngay->format('d/m');
                $data[] = (clone $query)->whereDate('ngay_dat', $ngay)->sum('thanh_tien');
            }
        } elseif ($kieu === 'tuan') {
            for ($i = 3; $i >= 0; $i--) {
                $bat_dau = $now->copy()->subWeeks($i)->startOfWeek();
                $ket_thuc = $now->copy()->subWeeks($i)->endOfWeek();
                $labels[] = 'Tuần ' . $bat_dau->format('d/m');
                $data[] = (clone $query)->whereBetween('ngay_dat', [$bat_dau, $ket_thuc])->sum('thanh_tien');
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $thang = $now->copy()->subMonths($i);
                $labels[] = $thang->format('m/Y');
                $data[] = (clone $query)->whereMonth('ngay_dat', $thang->month)->whereYear('ngay_dat', $thang->year)->sum('thanh_tien');
            }
        }

        $tongDoanhThu = array_sum($data);
        $hangHoaBanChay = SanPham::withCount(['chiTietDonHangs as da_ban' => fn($q) =>
            $q->whereHas('donHang', fn($q2) => $q2->where('trang_thai', 'da_hoan_thanh'))
        ])->orderByDesc('da_ban')->take(10)->get();

        $khachHangDoanhThu = User::where('vai_tro', 'khach_hang')
            ->withSum(['donHangs as tong_chi_tieu' => fn($q) => $q->where('trang_thai', 'da_hoan_thanh')], 'thanh_tien')
            ->orderByDesc('tong_chi_tieu')->take(10)->get();

        return view('admin.bao-cao.index', compact('labels', 'data', 'tongDoanhThu', 'kieu', 'hangHoaBanChay', 'khachHangDoanhThu'));
    }
}