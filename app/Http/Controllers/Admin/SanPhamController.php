<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\NhaCungCap;
use Illuminate\Http\Request;

class SanPhamController extends Controller
{
    private function uploadToCloudinary($file)
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'dvrclwek7');
        $apiKey    = env('CLOUDINARY_API_KEY', '937683635992285');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        $timestamp = time();
        $params    = ['folder' => 'hvpetshop/products', 'timestamp' => $timestamp];
        ksort($params);
        $strToSign = http_build_query($params) . $apiSecret;
        $signature = sha1($strToSign);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file'      => new \CURLFile($file->getRealPath(), $file->getMimeType(), $file->getClientOriginalName()),
            'api_key'   => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder'    => 'hvpetshop/products',
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['secure_url'] ?? null;
    }

    public function index(Request $request)
    {
        $query = SanPham::with(['danhMuc', 'nhaCungCap']);

        if ($request->tu_khoa) {
            $query->where(fn($q) => $q->where('ten_sp', 'like', '%'.$request->tu_khoa.'%')
                ->orWhere('ma_sp', 'like', '%'.$request->tu_khoa.'%'));
        }
        if ($request->danh_muc_id) $query->where('danh_muc_id', $request->danh_muc_id);
        if ($request->trang_thai !== null && $request->trang_thai !== '') {
            $query->where('trang_thai', $request->trang_thai);
        }

        $sanPhams = $query->latest()->paginate(15)->appends($request->all());
        $danhMucs = DanhMuc::all();
        $nhaCungCaps = NhaCungCap::where('trang_thai', true)->get();

        return view('admin.san-pham.index', compact('sanPhams', 'danhMucs', 'nhaCungCaps'));
    }

    public function create()
    {
        $danhMucs = DanhMuc::active()->get();
        $nhaCungCaps = NhaCungCap::where('trang_thai', true)->get();
        return view('admin.san-pham.create', compact('danhMucs', 'nhaCungCaps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_sp'      => 'required',
            'danh_muc_id' => 'required|exists:danh_mucs,id',
            'gia'         => 'required|numeric|min:0',
            'so_luong'    => 'required|integer|min:0',
            'hinh_anh'    => 'nullable|image|max:5120',
        ]);

        $maSP    = 'SP' . str_pad(SanPham::max('id') + 1, 4, '0', STR_PAD_LEFT);
        $hinhAnh = null;

        if ($request->hasFile('hinh_anh')) {
            $url = $this->uploadToCloudinary($request->file('hinh_anh'));
            if ($url) $hinhAnh = $url;
        }

        SanPham::create([
            'ma_sp'           => $maSP,
            'danh_muc_id'     => $request->danh_muc_id,
            'nha_cung_cap_id' => $request->nha_cung_cap_id,
            'ten_sp'          => $request->ten_sp,
            'mo_ta'           => $request->mo_ta,
            'hinh_anh'        => $hinhAnh,
            'gia'             => $request->gia,
            'so_luong'        => $request->so_luong,
            'so_luong_kho'    => $request->so_luong,
            'nguong_canh_bao' => $request->nguong_canh_bao ?? 5,
            'trang_thai'      => $request->boolean('trang_thai'),
            'la_moi'          => $request->boolean('la_moi'),
        ]);

        return redirect()->route('admin.san-pham.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit($id)
    {
        $sanPham     = SanPham::findOrFail($id);
        $danhMucs    = DanhMuc::all();
        $nhaCungCaps = NhaCungCap::where('trang_thai', true)->get();
        return view('admin.san-pham.edit', compact('sanPham', 'danhMucs', 'nhaCungCaps'));
    }

    public function update(Request $request, $id)
    {
        $sanPham = SanPham::findOrFail($id);
        $request->validate([
            'ten_sp'      => 'required',
            'danh_muc_id' => 'required|exists:danh_mucs,id',
            'gia'         => 'required|numeric|min:0',
            'so_luong'    => 'required|integer|min:0',
            'hinh_anh'    => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['ten_sp', 'danh_muc_id', 'nha_cung_cap_id', 'mo_ta', 'gia', 'so_luong', 'nguong_canh_bao']);
        $data['trang_thai'] = $request->boolean('trang_thai');
        $data['la_moi']     = $request->boolean('la_moi');

        if ($request->hasFile('hinh_anh')) {
            $url = $this->uploadToCloudinary($request->file('hinh_anh'));
            if ($url) $data['hinh_anh'] = $url;
        }

        $sanPham->update($data);
        return redirect()->route('admin.san-pham.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function toggleTrangThai($id)
    {
        $sanPham = SanPham::findOrFail($id);
        $sanPham->update(['trang_thai' => !$sanPham->trang_thai]);
        $msg = $sanPham->trang_thai ? 'Đã hiển thị sản phẩm!' : 'Đã ẩn sản phẩm!';
        return back()->with('success', $msg);
    }

    public function show($id)
    {
        $sanPham = SanPham::with(['danhMuc', 'nhaCungCap', 'danhGias.user'])->findOrFail($id);
        return view('admin.san-pham.show', compact('sanPham'));
    }
}