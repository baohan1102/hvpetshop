<?php

namespace App\Http\Controllers;

use App\Models\DiaChi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TaiKhoanController extends Controller
{
    public function __construct() { $this->middleware('auth'); }

    public function index()
    {
        $user = Auth::user();
        $diaChis = DiaChi::where('user_id', $user->id)->get();
        return view('tai-khoan.index', compact('user', 'diaChis'));
    }

    public function capNhat(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'ho_ten'        => 'required|min:2',
            'so_dien_thoai' => 'required|unique:users,so_dien_thoai,' . $user->id,
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'ngay_sinh'     => 'nullable|date',
        ]);

        $user->update($request->only(['ho_ten', 'so_dien_thoai', 'email', 'ngay_sinh', 'dia_chi']));
        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // Địa chỉ
    public function themDiaChi(Request $request)
    {
        $request->validate([
            'ho_ten'          => 'required',
            'so_dien_thoai'   => 'required',
            'dia_chi_chi_tiet'=> 'required',
            'tinh_thanh'      => 'required',
            'quan_huyen'      => 'required',
        ]);

        if ($request->la_mac_dinh) {
            DiaChi::where('user_id', Auth::id())->update(['la_mac_dinh' => false]);
        }

        $coMacDinh = DiaChi::where('user_id', Auth::id())->where('la_mac_dinh', true)->exists();

        DiaChi::create([
            'user_id'          => Auth::id(),
            'ho_ten'           => $request->ho_ten,
            'so_dien_thoai'    => $request->so_dien_thoai,
            'dia_chi_chi_tiet' => $request->dia_chi_chi_tiet,
            'tinh_thanh'       => $request->tinh_thanh,
            'quan_huyen'       => $request->quan_huyen,
            'la_mac_dinh'      => $request->boolean('la_mac_dinh') || !$coMacDinh,
        ]);

        return back()->with('success', 'Thêm địa chỉ thành công!');
    }

    public function datMacDinhDiaChi($id)
    {
        DiaChi::where('user_id', Auth::id())->update(['la_mac_dinh' => false]);
        DiaChi::where('user_id', Auth::id())->where('id', $id)->update(['la_mac_dinh' => true]);
        return back()->with('success', 'Đã đặt địa chỉ mặc định!');
    }

    public function xoaDiaChi($id)
    {
        $dc = DiaChi::where('user_id', Auth::id())->findOrFail($id);
        $laMacDinh = $dc->la_mac_dinh;
        $dc->delete();

        if ($laMacDinh) {
            DiaChi::where('user_id', Auth::id())->first()?->update(['la_mac_dinh' => true]);
        }

        return back()->with('success', 'Đã xóa địa chỉ!');
    }
}