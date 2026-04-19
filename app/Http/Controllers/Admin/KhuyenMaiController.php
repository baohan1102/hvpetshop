<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        $khuyenMais = KhuyenMai::latest()->paginate(15);
        return view('admin.khuyen-mai.index', compact('khuyenMais'));
    }

    public function create()
    {
        return view('admin.khuyen-mai.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'ma_km'            => 'required|unique:khuyen_mais,ma_km',
        'ten_chuong_trinh' => 'required',
        'loai'             => 'required|in:phan_tram,co_dinh,mien_phi_ship',
        'don_hang_toi_thieu'=> 'required|numeric|min:0',
        'ngay_bat_dau'     => 'required|date',
        'ngay_ket_thuc'    => 'required|date|after:ngay_bat_dau',
    ]);

    $data = $request->except('trang_thai'); // ❌ bỏ "on"
    $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0; // ✅ chuẩn int

    KhuyenMai::create($data);

    return redirect()->route('admin.khuyen-mai.index')
        ->with('success', 'Thêm mã khuyến mãi thành công!');
}

    public function edit($id)
    {
        return view('admin.khuyen-mai.edit', [
            'km' => KhuyenMai::findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
{
    $km = KhuyenMai::findOrFail($id);

    $data = $request->except('trang_thai'); // ❌ bỏ "on"
    $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0; // ✅ chuẩn int

    $km->update($data);

    return redirect()->route('admin.khuyen-mai.index')
        ->with('success', 'Cập nhật thành công!');
}
  public function destroy($id)
{
    $km = KhuyenMai::findOrFail($id);

    // Xóa các bản ghi liên quan trước
    \App\Models\KhuyenMaiSuDung::where('khuyen_mai_id', $id)->delete();

    // Bỏ liên kết với đơn hàng (set null)
    \App\Models\DonHang::where('khuyen_mai_id', $id)
        ->update(['khuyen_mai_id' => null]);

    $km->delete();

    return back()->with('success', 'Đã xóa mã khuyến mãi!');
}
}