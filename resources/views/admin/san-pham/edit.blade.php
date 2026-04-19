@extends('layouts.admin')
@section('title', 'Sửa sản phẩm')
@section('page-title', 'Sửa sản phẩm')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="stat-card">

```
        {{-- FORM UPDATE --}}
        <form action="{{ route('admin.san-pham.update', $sanPham->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tên sản phẩm *</label>
                    <input type="text" name="ten_sp" class="form-control" value="{{ old('ten_sp', $sanPham->ten_sp) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Danh mục *</label>
                    <select name="danh_muc_id" class="form-select" required>
                        @foreach($danhMucs as $dm)
                            <option value="{{ $dm->id }}" {{ $sanPham->danh_muc_id==$dm->id?'selected':'' }}>
                                {{ $dm->ten_danh_muc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Giá bán (đ) *</label>
                    <input type="number" name="gia" class="form-control" value="{{ old('gia', $sanPham->gia) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Số lượng</label>
                    <input type="number" name="so_luong" class="form-control" value="{{ old('so_luong', $sanPham->so_luong) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Ngưỡng cảnh báo</label>
                    <input type="number" name="nguong_canh_bao" class="form-control" value="{{ old('nguong_canh_bao', $sanPham->nguong_canh_bao) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nhà cung cấp</label>
                    <select name="nha_cung_cap_id" class="form-select">
                        <option value="">-- Không --</option>
                        @foreach($nhaCungCaps as $ncc)
                            <option value="{{ $ncc->id }}" {{ $sanPham->nha_cung_cap_id==$ncc->id?'selected':'' }}>
                                {{ $ncc->ten_ncc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Hình ảnh mới</label>
                    <input type="file" name="hinh_anh" class="form-control" accept="image/*" onchange="previewImg(this)">
                    <img id="imgPreview" src="{{ $sanPham->hinh_anh_url }}" class="mt-2 rounded" style="max-height:100px">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="mo_ta" class="form-control" rows="4">{{ old('mo_ta', $sanPham->mo_ta) }}</textarea>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="trang_thai" {{ $sanPham->trang_thai ? 'checked' : '' }}>
                        <label class="form-check-label">Hiển thị sản phẩm</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="la_moi" {{ $sanPham->la_moi ? 'checked' : '' }}>
                        <label class="form-check-label">Đánh dấu là mới</label>
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        Lưu thay đổi
                    </button>

                    <a href="{{ route('admin.san-pham.index') }}" class="btn btn-outline-secondary">
                        Hủy
                    </a>
                </div>
            </div>
        </form>

        {{-- FORM XÓA (TÁCH RIÊNG) --}}
        <div class="mt-3 text-end">
            <form action="{{ route('admin.san-pham.destroy', $sanPham->id) }}" 
                  method="POST"
                  onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Xóa sản phẩm
                </button>
            </form>
        </div>

    </div>
</div>


</div>

@push('scripts')

<script>
function previewImg(input) {
    const preview = document.getElementById('imgPreview');
    if(input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
    }
}
</script>

@endpush
@endsection
