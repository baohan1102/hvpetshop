{{-- resources/views/admin/san-pham/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Thêm sản phẩm')
@section('page-title', 'Thêm sản phẩm mới')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="stat-card">
            <form action="{{ route('admin.san-pham.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Tên sản phẩm *</label>
                        <input type="text" name="ten_sp" class="form-control @error('ten_sp') is-invalid @enderror" value="{{ old('ten_sp') }}" required>
                        @error('ten_sp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Danh mục *</label>
                        <select name="danh_muc_id" class="form-select @error('danh_muc_id') is-invalid @enderror" required>
                            <option value="">-- Chọn --</option>
                            @foreach($danhMucs as $dm)<option value="{{ $dm->id }}" {{ old('danh_muc_id')==$dm->id?'selected':'' }}>{{ $dm->ten_danh_muc }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá bán (đ) *</label>
                        <input type="number" name="gia" class="form-control @error('gia') is-invalid @enderror" value="{{ old('gia') }}" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Số lượng *</label>
                        <input type="number" name="so_luong" class="form-control @error('so_luong') is-invalid @enderror" value="{{ old('so_luong', 0) }}" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Cảnh báo tồn kho</label>
                        <input type="number" name="nguong_canh_bao" class="form-control" value="{{ old('nguong_canh_bao', 5) }}" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nhà cung cấp</label>
                        <select name="nha_cung_cap_id" class="form-select">
                            <option value="">-- Không chọn --</option>
                            @foreach($nhaCungCaps as $ncc)<option value="{{ $ncc->id }}">{{ $ncc->ten_ncc }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Hình ảnh</label>
                        <input type="file" name="hinh_anh" class="form-control" accept="image/*" onchange="previewImg(this)">
                        <img id="imgPreview" src="" alt="" class="mt-2 rounded" style="max-height:100px;display:none">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Mô tả sản phẩm</label>
                        <textarea name="mo_ta" class="form-control" rows="4" placeholder="Mô tả chi tiết sản phẩm...">{{ old('mo_ta') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="trang_thai" id="trangThai" checked>
                            <label class="form-check-label" for="trangThai">Hiển thị sản phẩm</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="la_moi" id="laMoi" checked>
                            <label class="form-check-label" for="laMoi">Đánh dấu là mới</label>
                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold px-4">Thêm sản phẩm</button>
                        <a href="{{ route('admin.san-pham.index') }}" class="btn btn-outline-secondary">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
function previewImg(input) {
    const preview = document.getElementById('imgPreview');
    if(input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
    }
}
</script>
@endpush
@endsection