<div class="card product-card h-100">
    <div class="position-relative">
        <a href="{{ route('san-pham.chi-tiet', $sanPham->id) }}">
            <img src="{{ $sanPham->hinh_anh_url }}" alt="{{ $sanPham->ten_sp }}" class="product-img">
        </a>
        @if($sanPham->la_moi)
        <span class="badge position-absolute top-0 start-0 m-2" style="background:var(--primary)">MỚI</span>
        @endif
        @auth
        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow-sm"
            onclick="toggleWishlist(this, {{ $sanPham->id }})" title="Yêu thích">
            <i class="bi bi-heart"></i>
        </button>
        @endauth
    </div>
    <div class="card-body d-flex flex-column">
        <small class="text-muted text-uppercase fw-bold mb-1" style="font-size:11px">{{ $sanPham->danhMuc->ten_danh_muc ?? '' }}</small>
        <a href="{{ route('san-pham.chi-tiet', $sanPham->id) }}" class="text-decoration-none text-dark">
            <h6 class="card-title mb-1 fw-semibold" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                {{ $sanPham->ten_sp }}
            </h6>
        </a>
        <div class="d-flex align-items-center gap-1 mb-2">
            @for($i = 1; $i <= 5; $i++)
            <i class="bi bi-star-fill star-filled" style="font-size:12px; {{ $i > $sanPham->danhGiaTrungBinh() ? 'opacity:.3' : '' }}"></i>
            @endfor
            <small class="text-muted">({{ $sanPham->soLuongDanhGia() }})</small>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-auto">
            <span class="fw-bold fs-6" style="color:var(--primary)">{{ number_format($sanPham->gia) }}đ</span>
            @auth
            <button class="btn btn-primary btn-sm rounded-circle btn-them-gio-hang"
                data-url="{{ route('gio-hang.them', $sanPham->id) }}"
                title="Thêm vào giỏ">
                <i class="bi bi-cart-plus"></i>
            </button>
            @endauth
        </div>
    </div>
</div>