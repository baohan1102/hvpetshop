<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;

class DanhMucController extends Controller
{
    public function index() { return view('admin.danh-muc.index', ['danhMucs' => DanhMuc::paginate(15)]); }
    public function store(Request $request) {
        $request->validate(['ten_danh_muc' => 'required']);
        DanhMuc::create(['ten_danh_muc' => $request->ten_danh_muc, 'trang_thai' => true]);
        return back()->with('success', 'Thêm danh mục thành công!');
    }
    public function update(Request $request, $id) {
        DanhMuc::findOrFail($id)->update(['ten_danh_muc' => $request->ten_danh_muc]);
        return back()->with('success', 'Cập nhật thành công!');
    }
    public function toggleTrangThai($id) {
        $dm = DanhMuc::findOrFail($id);
        $dm->update(['trang_thai' => !$dm->trang_thai]);
        return back()->with('success', $dm->trang_thai ? 'Đã hiện danh mục!' : 'Đã ẩn danh mục!');
    }
}