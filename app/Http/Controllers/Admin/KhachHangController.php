<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DonHang;
use Illuminate\Http\Request;

class KhachHangController extends Controller
{
    public function index(Request $request) {
        $query = User::where('vai_tro', 'khach_hang')
            ->withSum(['donHangs as tong_chi_tieu' => fn($q) => $q->where('trang_thai', 'da_hoan_thanh')], 'thanh_tien')
            ->withCount(['donHangs as so_don' => fn($q) => $q->where('trang_thai', 'da_hoan_thanh')]);
        if ($request->tu_khoa) $query->where(fn($q) => $q->where('ho_ten', 'like', '%'.$request->tu_khoa.'%')->orWhere('so_dien_thoai', 'like', '%'.$request->tu_khoa.'%'));
        $khachHangs = $query->orderByDesc('tong_chi_tieu')->paginate(15)->appends($request->all());
        return view('admin.khach-hang.index', compact('khachHangs'));
    }

    public function show($id) {
        $kh = User::where('vai_tro', 'khach_hang')->findOrFail($id);
        $donHangs = DonHang::where('user_id', $id)->with('chiTiets.sanPham')->latest()->paginate(10);
        return view('admin.khach-hang.show', compact('kh', 'donHangs'));
    }
}