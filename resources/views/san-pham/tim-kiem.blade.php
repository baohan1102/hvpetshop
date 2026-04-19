@extends('layouts.app')

@section('title', 'Tìm kiếm sản phẩm')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold mb-4">
        <i class="bi bi-search me-2 text-primary"></i>Kết quả tìm kiếm
    </h2>

    {{-- FORM SEARCH --}}
    <form method="GET" action="{{ route('tim-kiem') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="tu_khoa" class="form-control"
                   placeholder="Nhập tên sản phẩm..."
                   value="{{ request('tu_khoa') }}">
            <button class="btn btn-primary">
                <i class="bi bi-search"></i> Tìm
            </button>
        </div>
    </form>

    {{-- HIỂN THỊ KEYWORD --}}
    @if(request('tu_khoa'))
        <p class="text-muted mb-4">
            Kết quả cho: <strong>"{{ request('tu_khoa') }}"</strong>
        </p>
    @endif

    {{-- LIST --}}
    <div class="row g-4">

        @forelse($sanPhams as $sp)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">

                <a href="{{ route('san-pham.chi-tiet', $sp->id) }}">
                    <img src="{{ $sp->hinh_anh_url }}"
                         class="card-img-top"
                         style="height:200px; object-fit:cover; border-radius:12px 12px 0 0;">
                </a>

                <div class="card-body d-flex flex-column">

                    <h6 class="fw-semibold mb-2" style="min-height:40px">
                        {{ $sp->ten_sp }}
                    </h6>

                    <div class="fw-bold text-danger mb-3">
                        {{ number_format($sp->gia) }} đ
                    </div>

                    <form action="{{ route('gio-hang.them', $sp->id) }}" method="POST" class="mt-auto">
                        @csrf
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </button>
                    </form>

                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted">
            Không tìm thấy sản phẩm phù hợp
        </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $sanPhams->appends(request()->query())->links() }}
    </div>

</div>
@endsection