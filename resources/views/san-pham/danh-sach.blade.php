{{-- resources/views/san-pham/danh-sach.blade.php --}}
@extends('layouts.app')
@section('title','Sản phẩm - HV Pet Shop')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('home') }}" class="text-muted small">Trang chủ</a>
        <span class="text-muted">/</span>
        <span class="small fw-semibold">Sản phẩm cho thú cưng</span>
    </div>
    <div class="row g-4">
        {{-- BỘ LỌC --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 sticky-top" style="top:80px">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Bộ lọc</h6>
                    <a href="{{ route('san-pham.danh-sach') }}" class="text-primary small">Xóa tất cả</a>
                </div>
                <form method="GET" action="{{ route('san-pham.danh-sach') }}">
                    <div class="mb-3">
                        <input type="text" name="tu_khoa" class="form-control form-control-sm" placeholder="Tìm kiếm..." value="{{ request('tu_khoa') }}">
                    </div>
                    <div class="mb-3">
                        <div class="fw-semibold small text-uppercase text-muted mb-2">Loại thú cưng</div>
                        @foreach($danhMucs as $dm)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="danh_muc" id="dm{{ $dm->id }}" value="{{ $dm->id }}" {{ request('danh_muc')==$dm->id?'checked':'' }}>
                            <label class="form-check-label small" for="dm{{ $dm->id }}">{{ $dm->ten_danh_muc }}</label>
                        </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <div class="fw-semibold small text-uppercase text-muted mb-2">Khoảng giá</div>
                        @foreach(['duoi100'=>'Dưới 100.000đ','100-500'=>'100k - 500k','tren500'=>'Trên 500.000đ'] as $v=>$l)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="khoang_gia" id="kg{{ $v }}" value="{{ $v }}" {{ request('khoang_gia')===$v?'checked':'' }}>
                            <label class="form-check-label small" for="kg{{ $v }}">{{ $l }}</label>
                        </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Áp dụng</button>
                </form>
            </div>
        </div>

        {{-- DANH SÁCH --}}
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold mb-0">Sản phẩm cho thú cưng</h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Hiện {{ $sanPhams->count() }} / {{ $sanPhams->total() }} sản phẩm</span>
                    <form method="GET">
                        @foreach(request()->except('sap_xep') as $k=>$v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <select name="sap_xep" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="moi_nhat" {{ request('sap_xep')==='moi_nhat'?'selected':'' }}>Mới nhất</option>
                            <option value="gia_tang" {{ request('sap_xep')==='gia_tang'?'selected':'' }}>Giá tăng dần</option>
                            <option value="gia_giam" {{ request('sap_xep')==='gia_giam'?'selected':'' }}>Giá giảm dần</option>
                            <option value="ban_chay" {{ request('sap_xep')==='ban_chay'?'selected':'' }}>Bán chạy nhất</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="row g-3">
                @forelse($sanPhams as $sp)
                <div class="col-6 col-md-4">@include('components.product-card', ['sanPham' => $sp])</div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Không tìm thấy sản phẩm</h5>
                    <a href="{{ route('san-pham.danh-sach') }}" class="btn btn-primary mt-2">Xem tất cả sản phẩm</a>
                </div>
                @endforelse
            </div>
            <div class="mt-4">{{ $sanPhams->links() }}</div>
        </div>
    </div>
</div>
@endsection