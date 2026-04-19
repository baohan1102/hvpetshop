<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NhanVienController extends Controller
{
    public function index(Request $request) {
        $query = User::where('vai_tro', 'nhan_vien');
        if ($request->tu_khoa) $query->where(fn($q) => $q->where('ho_ten', 'like', '%'.$request->tu_khoa.'%')->orWhere('so_dien_thoai', 'like', '%'.$request->tu_khoa.'%'));
        return view('admin.nhan-vien.index', ['nhanViens' => $query->paginate(15)]);
    }
    public function store(Request $request) {
        $request->validate(['ho_ten' => 'required', 'so_dien_thoai' => 'required|unique:users,so_dien_thoai']);
        User::create([
            'ho_ten' => $request->ho_ten, 'so_dien_thoai' => $request->so_dien_thoai,
            'email' => $request->email, 'mat_khau' => Hash::make('1111'),
            'vai_tro' => 'nhan_vien', 'trang_thai' => true, 'mat_khau_mac_dinh' => true,
        ]);
        return back()->with('success', 'Thêm nhân viên thành công! Mật khẩu: 1111');
    }
    public function capLaiMatKhau($id) {
        User::where('vai_tro', 'nhan_vien')->findOrFail($id)->update(['mat_khau' => Hash::make('1111'), 'mat_khau_mac_dinh' => true]);
        return back()->with('success', 'Đã cấp lại mật khẩu: 1111');
    }
    public function toggleTrangThai($id) {
        $nv = User::where('vai_tro', 'nhan_vien')->findOrFail($id);
        $nv->update(['trang_thai' => !$nv->trang_thai]);
        return back()->with('success', $nv->trang_thai ? 'Đã mở khóa!' : 'Đã khóa!');
    }
}