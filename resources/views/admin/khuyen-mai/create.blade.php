{{-- resources/views/admin/khuyen-mai/create.blade.php --}}
@extends('layouts.admin')
@section('title','Tạo mã KM')
@section('page-title','Tạo mã khuyến mãi')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="stat-card">
<form action="{{ route('admin.khuyen-mai.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Mã khuyến mãi *</label>
            <input type="text" name="ma_km" class="form-control text-uppercase" required placeholder="VD: SALE20">
            <div class="form-text">Mã viết hoa, không dấu, không khoảng trắng</div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Tên chương trình *</label>
            <input type="text" name="ten_chuong_trinh" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Loại khuyến mãi *</label>
            <select name="loai" class="form-select" id="loaiKM" required onchange="toggleLoai()">
                <option value="phan_tram">Giảm theo %</option>
                <option value="co_dinh">Giảm số tiền cố định</option>
                <option value="mien_phi_ship">Miễn phí vận chuyển</option>
            </select>
        </div>
        <div class="col-md-4" id="fieldPhanTram">
            <label class="form-label fw-semibold">% Giảm</label>
            <input type="number" name="ty_le_giam" class="form-control" min="1" max="100" step="0.01" value="0">
        </div>
        <div class="col-md-4" id="fieldCoDinh" style="display:none">
            <label class="form-label fw-semibold">Số tiền giảm (đ)</label>
            <input type="number" name="so_tien_giam" class="form-control" min="0" value="0">
        </div>
        <div class="col-md-4" id="fieldGiamToiDa">
            <label class="form-label fw-semibold">Giảm tối đa (đ)</label>
            <input type="number" name="giam_toi_da" class="form-control" placeholder="Để trống = không giới hạn">
        </div>
        <div class="col-md-4" id="fieldMienPhiShipTu" style="display:none">
            <label class="form-label fw-semibold">Đơn từ (đ) được free ship</label>
            <input type="number" name="mien_phi_ship_tu" class="form-control" value="0">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Đơn tối thiểu (đ) *</label>
            <input type="number" name="don_hang_toi_thieu" class="form-control" value="0" min="0" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tổng số mã</label>
            <input type="number" name="so_luong_ma" class="form-control" placeholder="Để trống = không giới hạn">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Giới hạn mỗi KH</label>
            <input type="number" name="gioi_han_moi_kh" class="form-control" placeholder="Để trống = không giới hạn">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Ngày bắt đầu *</label>
            <input type="date" name="ngay_bat_dau" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Ngày kết thúc *</label>
            <input type="date" name="ngay_ket_thuc" class="form-control" required>
        </div>
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="trang_thai" checked>
                <label class="form-check-label">Kích hoạt ngay</label>
            </div>
        </div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary fw-bold">Tạo mã khuyến mãi</button>
            <a href="{{ route('admin.khuyen-mai.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </div>
</form>
</div>
</div>
</div>
@push('scripts')
<script>
function toggleLoai() {
    const loai = document.getElementById('loaiKM').value;
    document.getElementById('fieldPhanTram').style.display = loai==='phan_tram' ? '' : 'none';
    document.getElementById('fieldCoDinh').style.display = loai==='co_dinh' ? '' : 'none';
    document.getElementById('fieldGiamToiDa').style.display = loai!=='co_dinh' ? '' : 'none';
    document.getElementById('fieldMienPhiShipTu').style.display = loai==='mien_phi_ship' ? '' : 'none';
}
toggleLoai();
</script>
@endpush
@endsection