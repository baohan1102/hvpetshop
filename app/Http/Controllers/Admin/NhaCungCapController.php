<?php
// app/Http/Controllers/Admin/NhaCungCapController.php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\NhaCungCap;
use Illuminate\Http\Request;

class NhaCungCapController extends Controller
{
    public function index() { return view('admin.nha-cung-cap.index', ['nhaCungCaps' => NhaCungCap::paginate(15)]); }
    public function create() { return view('admin.nha-cung-cap.create'); }
    public function store(Request $request) {
        $request->validate(['ten_ncc' => 'required']);
        NhaCungCap::create($request->all() + ['trang_thai' => true]);
        return redirect()->route('admin.nha-cung-cap.index')->with('success', 'Thêm thành công!');
    }
    public function edit($id) { return view('admin.nha-cung-cap.edit', ['ncc' => NhaCungCap::findOrFail($id)]); }
    public function update(Request $request, $id) {
        NhaCungCap::findOrFail($id)->update($request->all());
        return redirect()->route('admin.nha-cung-cap.index')->with('success', 'Cập nhật thành công!');
    }
    public function destroy($id) { NhaCungCap::findOrFail($id)->delete(); return back()->with('success', 'Đã xóa!'); }
}