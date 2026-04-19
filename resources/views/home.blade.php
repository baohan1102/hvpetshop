@extends('layouts.app')
@section('title', 'HV Pet Shop - Thiên đường thú cưng')
@section('content')

{{-- HERO --}}
<section class="py-0">
    <div class="container-fluid px-0">
        <div class="hero-banner rounded-4 mx-3 mt-3 overflow-hidden position-relative" style="background: linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%); min-height:420px;">
            <div class="row align-items-center h-100 px-5 py-5">
                <div class="col-md-6 text-white">
                    <span class="badge mb-3 px-3 py-2" style="background:var(--primary)">MỚI NHẤT 2026</span>
                    <h1 class="display-4 fw-bold">Thiên đường cho<br><span style="color:var(--primary)">Thú Cưng</span> của bạn</h1>
                    <p class="lead opacity-75 mb-4">Khám phá bộ sưu tập sản phẩm cao cấp được tuyển chọn kỹ lưỡng dành riêng cho chó và mèo.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('san-pham.danh-sach') }}" class="btn btn-primary btn-lg px-4">Mua sắm ngay <i class="bi bi-arrow-right"></i></a>
                        <a href="#khuyen-mai" class="btn btn-outline-light btn-lg px-4">Xem ưu đãi</a>
                    </div>
                </div>
                <div class="col-md-6 text-end d-none d-md-block">
                    <div style="font-size:200px; opacity:.15;">🐾</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- DANH MỤC PHỔ BIẾN --}}
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0 fw-bold">Danh mục phổ biến</h2>
            <a href="{{ route('san-pham.danh-sach') }}" class="text-primary">Xem tất cả <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            @foreach($danhMucs->take(4) as $dm)
            <div class="col-6 col-md-3">
                <a href="{{ route('san-pham.danh-sach', ['danh_muc' => $dm->id]) }}" class="text-decoration-none">
                    <div class="card border-0 rounded-3 overflow-hidden text-center py-4 h-100" style="background: {{ ['#FFF3E0','#E3F2FD','#F3E5F5','#E8F5E9'][$loop->index % 4] }}; transition: transform .2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                        <div class="fs-1 mb-2">{{ ['🍖','🎾','📿','🛏'][($loop->index) % 4] }}</div>
                        <h6 class="fw-bold text-dark mb-1">{{ $dm->ten_danh_muc }}</h6>
                        <small class="text-muted">{{ $dm->san_phams_count ?? 0 }} sản phẩm</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SẢN PHẨM MỚI NHẤT --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0 fw-bold">Sản phẩm mới nhất</h2>
            <div>
                <button class="btn btn-outline-secondary btn-sm me-1" id="prevSlide"><i class="bi bi-chevron-left"></i></button>
                <button class="btn btn-outline-secondary btn-sm" id="nextSlide"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
        <div class="row g-4">
            @foreach($sanPhamsMoi as $sp)
            <div class="col-6 col-md-3">
                @include('components.product-card', ['sanPham' => $sp])
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SẢN PHẨM BÁN CHẠY --}}
<section class="py-5">
    <div class="container">
        <h2 class="section-title fw-bold">🔥 Bán chạy nhất</h2>
        <div class="row g-4">
            @foreach($sanPhamBanChay as $sp)
            <div class="col-6 col-md-3">
                @include('components.product-card', ['sanPham' => $sp])
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- BANNER KHUYẾN MÃI --}}
<section id="khuyen-mai" class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="rounded-4 p-4 h-100 d-flex align-items-center" style="background:linear-gradient(135deg,#00BCD4,#0097A7)">
                    <div class="text-white">
                        <h3 class="fw-bold">🚀 Miễn phí vận chuyển</h3>
                        <p>Cho đơn hàng từ 100.000đ. Áp dụng toàn quốc!</p>
                        <a href="{{ route('san-pham.danh-sach') }}" class="btn btn-light fw-bold">Mua ngay</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="rounded-4 p-4 h-100 d-flex align-items-center" style="background:linear-gradient(135deg,#FF7043,#E64A19)">
                    <div class="text-white">
                        <h3 class="fw-bold">🎁 Ưu đãi hội viên</h3>
                        <p>Đăng ký ngay để nhận mã giảm giá 10% cho đơn đầu tiên!</p>
                        <a href="{{ route('register') }}" class="btn btn-light fw-bold">Đăng ký</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection