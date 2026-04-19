@extends('layouts.admin')
@section('title','Sửa mã KM')
@section('page-title','Sửa mã khuyến mãi')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<div class="stat-card">
<form action="{{ route('admin.khuyen-mai.update', $km->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Mã khuyến mãi</label>
            <input type="text" class="form-control bg-light" value="{{ $km->ma_km }}" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Tên chương trình *</label>
            <input type="text" name="ten_chuong_trinh" class="form-control" value="{{ $km->ten_chuong_trinh }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Loại</label>
            <select name="loai" class="form-select" id="loaiKM" onchange="toggleLoai()">
                <option value="phan_tram" {{ $km->loai==='phan_tram'?'selected':'' }}>Giảm theo %</option>
                <option value="co_dinh" {{ $km->loai==='co_dinh'?'selected':'' }}>Giảm cố định</option>
                <option value="mien_phi_ship" {{ $km->loai==='mien_phi_ship'?'selected':'' }}>Miễn phí ship</option>
            </select>
        </div>
        <div class="col-md-4" id="fieldPhanTram">
            <label class="form-label fw-semibold">% Giảm</label>
            <input type="number" name="ty_le_giam" class="form-control" value="{{ $km->ty_le_giam }}" min="0" max="100">
        </div>
        <div class="col-md-4" id="fieldCoDinh">
            <label class="form-label fw-semibold">Số tiền giảm (đ)</label>
            <input type="number" name="so_tien_giam" class="form-control" value="{{ $km->so_tien_giam }}" min="0">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Giảm tối đa (đ)</label>
            <input type="number" name="giam_toi_da" class="form-control" value="{{ $km->giam_toi_da }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Đơn tối thiểu (đ)</label>
            <input type="number" name="don_hang_toi_thieu" class="form-control" value="{{ $km->don_hang_toi_thieu }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tổng số mã</label>
            <input type="number" name="so_luong_ma" class="form-control" value="{{ $km->so_luong_ma }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Giới hạn mỗi KH</label>
            <input type="number" name="gioi_han_moi_kh" class="form-control" value="{{ $km->gioi_han_moi_kh }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Ngày bắt đầu</label>
            <input type="date" name="ngay_bat_dau" class="form-control" value="{{ $km->ngay_bat_dau->format('Y-m-d') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Ngày kết thúc</label>
            <input type="date" name="ngay_ket_thuc" class="form-control" value="{{ $km->ngay_ket_thuc->format('Y-m-d') }}" required>
        </div>
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="trang_thai" {{ $km->trang_thai ? 'checked' : '' }}>
                <label class="form-check-label">Kích hoạt</label>
            </div>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary fw-bold">Lưu thay đổi</button>
            <a href="{{ route('admin.khuyen-mai.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </div>
</form>
</div>
</div></div>
@push('scripts')
<script>
function toggleLoai() {
    const loai = document.getElementById('loaiKM').value;
    document.getElementById('fieldPhanTram').style.display = loai==='phan_tram' ? '' : 'none';
    document.getElementById('fieldCoDinh').style.display = loai==='co_dinh' ? '' : 'none';
}
toggleLoai();
</script>
@endpush
@endsection