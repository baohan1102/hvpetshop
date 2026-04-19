@extends('layouts.admin')
@section('title','Chi tiết sản phẩm')
@section('page-title','Chi tiết sản phẩm')
@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <div class="stat-card text-center">
            <img src="{{ $sanPham->hinh_anh_url }}" alt="{{ $sanPham->ten_sp }}" class="img-fluid rounded-3 mb-3" style="max-height:280px;object-fit:contain">
            <h5 class="fw-bold">{{ $sanPham->ten_sp }}</h5>
            <span class="badge bg-secondary">{{ $sanPham->ma_sp }}</span>
        </div>
    </div>
    <div class="col-md-7">
        <div class="stat-card mb-4">
            <h6 class="fw-bold mb-3">Thông tin sản phẩm</h6>
            <div class="row g-2">
                <div class="col-6"><div class="text-muted small">Danh mục</div><div class="fw-semibold">{{ $sanPham->danhMuc->ten_danh_muc ?? '-' }}</div></div>
                <div class="col-6"><div class="text-muted small">Giá bán</div><div class="fw-bold" style="color:var(--primary)">{{ number_format($sanPham->gia) }}đ</div></div>
                <div class="col-6"><div class="text-muted small">Tồn kho</div><div class="fw-semibold">{{ $sanPham->so_luong }}</div></div>
                <div class="col-6"><div class="text-muted small">Nhà cung cấp</div><div class="fw-semibold">{{ $sanPham->nhaCungCap->ten_ncc ?? '-' }}</div></div>
                <div class="col-6"><div class="text-muted small">Trạng thái</div><span class="badge {{ $sanPham->trang_thai ? 'bg-success' : 'bg-secondary' }}">{{ $sanPham->trang_thai ? 'Hiển thị' : 'Đã ẩn' }}</span></div>
                <div class="col-6"><div class="text-muted small">Đánh giá TB</div>
                    <div>@for($i=1;$i<=5;$i++)<i class="bi bi-star-fill text-warning {{ $i > $sanPham->danhGiaTrungBinh() ? 'opacity-25':'' }}" style="font-size:14px"></i>@endfor <strong>{{ $sanPham->danhGiaTrungBinh() }}</strong></div>
                </div>
            </div>
            @if($sanPham->mo_ta)
            <div class="mt-3"><div class="text-muted small mb-1">Mô tả</div><p class="text-muted">{{ $sanPham->mo_ta }}</p></div>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.san-pham.edit', $sanPham->id) }}" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Sửa</a>
            <a href="{{ route('admin.san-pham.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        </div>
    </div>
</div>

{{-- Đánh giá --}}
<div class="stat-card mt-4">
    <h6 class="fw-bold mb-3">⭐ Đánh giá khách hàng ({{ $sanPham->soLuongDanhGia() }})</h6>
    @forelse($sanPham->danhGias()->with('user')->latest()->get() as $dg)
    <div class="border-bottom pb-3 mb-3">
        <div class="d-flex justify-content-between">
            <div class="fw-semibold">{{ $dg->user->ho_ten }}</div>
            <small class="text-muted">{{ $dg->created_at->format('d/m/Y') }}</small>
        </div>
        <div>@for($i=1;$i<=5;$i++)<i class="bi bi-star-fill text-warning {{ $i > $dg->so_sao ? 'opacity-25':'' }}"></i>@endfor</div>
        @if($dg->nhan_xet)<p class="text-muted small mt-1 mb-0">{{ $dg->nhan_xet }}</p>@endif
        @if($dg->hinh_anh)<img src="{{ $dg->hinh_anh_url }}" alt="" class="rounded mt-1" style="max-height:80px">@endif
    </div>
    @empty
    <p class="text-muted">Chưa có đánh giá nào.</p>
    @endforelse
</div>
@endsection