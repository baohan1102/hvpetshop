<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PhieuNhapKho;
use App\Models\SanPham;
use App\Models\NhaCungCap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KhoController extends Controller
{
    public function index(Request $request) {
        $query = SanPham::with(['nhaCungCap', 'danhMuc']);
        if ($request->tu_khoa) $query->where('ten_sp', 'like', '%'.$request->tu_khoa.'%');
        $sanPhams = $query->paginate(15)->appends($request->all());
        $phieuNhaps = PhieuNhapKho::with(['sanPham', 'nhaCungCap', 'nguoiTao'])->latest()->take(20)->get();
        $nhaCungCaps = NhaCungCap::where('trang_thai', true)->get();
        $sanPhamList = SanPham::where('trang_thai', true)->get();
        return view('admin.kho.index', compact('sanPhams', 'phieuNhaps', 'nhaCungCaps', 'sanPhamList'));
    }

    public function nhapKho(Request $request) {
        $request->validate([
            'san_pham_id' => 'required|exists:san_phams,id',
            'so_luong' => 'required|integer|min:1',
            'gia_nhap' => 'required|numeric|min:0',
        ]);
        $maNK = 'NK' . strtoupper(uniqid());
        PhieuNhapKho::create([
            'ma_nk' => $maNK, 'san_pham_id' => $request->san_pham_id,
            'nha_cung_cap_id' => $request->nha_cung_cap_id, 'nguoi_tao_id' => Auth::id(),
            'so_luong' => $request->so_luong, 'gia_nhap' => $request->gia_nhap,
            'tong_tien' => $request->so_luong * $request->gia_nhap, 'ghi_chu' => $request->ghi_chu,
        ]);
        SanPham::findOrFail($request->san_pham_id)->increment('so_luong', $request->so_luong);
        return back()->with('success', 'Nhập kho thành công!');
    }

    public function thongKe() {
        $sanPhamHetHang = SanPham::where('trang_thai', true)->where('so_luong', 0)->get();
        $sanPhamGanHet = SanPham::where('trang_thai', true)->whereColumn('so_luong', '<=', 'nguong_canh_bao')->where('so_luong', '>', 0)->get();
        $tongNhap = PhieuNhapKho::sum('so_luong');
        return view('admin.kho.thong-ke', compact('sanPhamHetHang', 'sanPhamGanHet', 'tongNhap'));
    }
}