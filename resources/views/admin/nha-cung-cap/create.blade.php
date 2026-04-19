{{-- resources/views/admin/nha-cung-cap/create.blade.php --}}
@extends('layouts.admin')
@section('title','Thêm nhà cung cấp')
@section('page-title','Thêm nhà cung cấp')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="stat-card">
<form action="{{ route('admin.nha-cung-cap.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-12"><label class="form-label fw-semibold">Tên nhà cung cấp *</label>
            <input type="text" name="ten_ncc" class="form-control" required value="{{ old('ten_ncc') }}"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Số điện thoại</label>
            <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
        <div class="col-12"><label class="form-label fw-semibold">Địa chỉ</label>
            <input type="text" name="dia_chi" class="form-control" value="{{ old('dia_chi') }}"></div>
        <div class="col-12"><label class="form-label fw-semibold">Ghi chú</label>
            <textarea name="ghi_chu" class="form-control" rows="3">{{ old('ghi_chu') }}</textarea></div>
        <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary fw-bold">Thêm nhà cung cấp</button>
            <a href="{{ route('admin.nha-cung-cap.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </div>
</form>
</div>
</div></div>
@endsection

{{-- resources/views/admin/nha-cung-cap/edit.blade.php --}}