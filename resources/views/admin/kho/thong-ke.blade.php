{{-- resources/views/admin/kho/thong-ke.blade.php --}}
@extends('layouts.admin')
@section('title','Thống kê kho')
@section('page-title','Thống kê tồn kho')
@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="stat-card">
            <h6 class="fw-bold mb-3 text-danger">🚨 Sản phẩm hết hàng ({{ $sanPhamHetHang->count() }})</h6>
            @forelse($sanPhamHetHang as $sp)
            <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-danger bg-opacity-10 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ $sp->hinh_anh_url }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:6px">
                    <div>
                        <div class="fw-semibold small">{{ Str::limit($sp->ten_sp, 30) }}</div>
                        <small class="text-muted">{{ $sp->ma_sp }}</small>
                    </div>
                </div>
                <span class="badge bg-danger">HẾT HÀNG</span>
            </div>
            @empty
            <p class="text-muted text-center py-3">✓ Không có sản phẩm nào hết hàng</p>
            @endforelse
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <h6 class="fw-bold mb-3 text-warning">⚠️ Sắp hết hàng ({{ $sanPhamGanHet->count() }})</h6>
            @forelse($sanPhamGanHet as $sp)
            <div class="d-flex justify-content-between align-items-center p-2 mb-2 bg-warning bg-opacity-10 rounded-3">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ $sp->hinh_anh_url }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:6px">
                    <div>
                        <div class="fw-semibold small">{{ Str::limit($sp->ten_sp, 30) }}</div>
                        <small class="text-muted">Tồn: {{ $sp->so_luong }} / Ngưỡng: {{ $sp->nguong_canh_bao }}</small>
                    </div>
                </div>
                <span class="badge bg-warning text-dark">{{ $sp->so_luong }} còn</span>
            </div>
            @empty
            <p class="text-muted text-center py-3">✓ Không có sản phẩm nào sắp hết</p>
            @endforelse
        </div>
    </div>
</div>
<div class="mt-3">
    <a href="{{ route('admin.kho.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Quay lại kho</a>
</div>
@endsection