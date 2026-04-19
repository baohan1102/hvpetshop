<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HV Pet Shop - Thiên đường thú cưng')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary: #00BCD4; --primary-dark: #0097A7; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; }
        .navbar-brand { font-weight: 700; color: var(--primary) !important; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .badge-primary { background: var(--primary); }
        .cart-count { position: absolute; top: -5px; right: -8px; background: #f44336; color: #fff; border-radius: 50%; padding: 1px 5px; font-size: 11px; }
        .product-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.08); transition: transform .2s, box-shadow .2s; overflow: hidden; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.15); }
        .product-img { height: 200px; object-fit: cover; width: 100%; }
        .star-filled { color: #FFC107; }
        .breadcrumb { background: none; padding: 0; }
        .section-title { position: relative; padding-bottom: 10px; margin-bottom: 24px; }
        .section-title::after { content: ''; position: absolute; bottom: 0; left: 0; width: 50px; height: 3px; background: var(--primary); border-radius: 2px; }
        footer { background: #1a1a2e; color: #ccc; }
        footer a { color: #aaa; text-decoration: none; }
        footer a:hover { color: var(--primary); }
        .toast-container { position: fixed; top: 80px; right: 20px; z-index: 9999; }
           nav[aria-label="pagination"] svg,
.pagination svg {
    width: 0.8em;
    height: 0.8em;
    vertical-align: middle;
}
   </style>
    @stack('styles')
</head>
<body>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <i class="bi bi-paw-print-fill fs-4" style="color:var(--primary)"></i> HV Pet Shop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                @foreach(\App\Models\DanhMuc::active()->take(4)->get() as $dm)
                <li class="nav-item"><a class="nav-link" href="{{ route('san-pham.danh-sach', ['danh_muc' => $dm->id]) }}">{{ $dm->ten_danh_muc }}</a></li>
                @endforeach
                <li class="nav-item"><a class="nav-link" href="{{ route('san-pham.danh-sach') }}">Tất cả</a></li>
            </ul>
            <form class="d-flex me-3" action="{{ route('tim-kiem') }}" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Tìm kiếm sản phẩm..." value="{{ request('q') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
            <div class="d-flex align-items-center gap-3">
                @auth
                <a href="{{ route('gio-hang') }}" class="position-relative text-dark fs-5">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-count" id="cart-count">{{ \App\Models\GioHang::where('user_id', auth()->id())->count() }}</span>
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Str::limit(auth()->user()->ho_ten, 12) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if(auth()->user()->isAdmin())
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Quản trị</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('tai-khoan.index') }}"><i class="bi bi-person me-2"></i>Tài khoản</a></li>
                        <li><a class="dropdown-item" href="{{ route('don-hang.lich-su') }}"><i class="bi bi-bag me-2"></i>Đơn hàng</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- ALERTS -->
<div class="toast-container">
    @if(session('success'))
    <div class="toast show align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex"><div class="toast-body"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
    </div>
    @endif
    @if(session('warning'))
    <div class="toast show align-items-center text-white bg-warning border-0" role="alert">
        <div class="d-flex"><div class="toast-body"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
    </div>
    @endif
    @if($errors->any())
    <div class="toast show align-items-center text-white bg-danger border-0" role="alert">
        <div class="d-flex"><div class="toast-body"><i class="bi bi-x-circle me-2"></i>{{ $errors->first() }}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>
    </div>
    @endif
</div>

<main>@yield('content')</main>

<!-- FOOTER -->
<footer class="py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-paw-print-fill fs-4" style="color:var(--primary)"></i>
                    <span class="fw-bold text-white fs-5">HV Pet Shop</span>
                </div>
                <p class="small">Chuyên cung cấp các sản phẩm chăm sóc thú cưng uy tín hàng đầu. Sự hài lòng của bạn và sức khỏe thú cưng là ưu tiên hàng đầu.</p>
            </div>
            <div class="col-md-3">
                <h6 class="text-white fw-bold mb-3">Liên kết</h6>
                <ul class="list-unstyled small">
                    <li><a href="#">Về chúng tôi</a></li>
                    <li><a href="#">Hệ thống cửa hàng</a></li>
                    <li><a href="#">Chính sách vận chuyển</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white fw-bold mb-3">Hỗ trợ</h6>
                <ul class="list-unstyled small">
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                    <li><a href="#">Hướng dẫn mua hàng</a></li>
                    <li><a href="#">Đổi trả hàng hóa</a></li>
                    <li><a href="{{ route('don-hang.lich-su') }}">Theo dõi đơn hàng</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white fw-bold mb-3">Liên hệ</h6>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-geo-alt me-2" style="color:var(--primary)"></i>123 Đường Thú Cưng, NVC, TP.CT</li>
                    <li><i class="bi bi-telephone me-2" style="color:var(--primary)"></i>1900 1234</li>
                    <li><i class="bi bi-envelope me-2" style="color:var(--primary)"></i>contact@hvpetshop.com</li>
                </ul>
                <h6 class="text-white fw-bold mt-3 mb-2">Bản tin</h6>
                <div class="input-group input-group-sm">
                    <input type="email" class="form-control" placeholder="Email của bạn">
                    <button class="btn btn-primary">Gửi</button>
                </div>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="mb-0 small">© 2026 HV Pet Shop. All rights reserved.</p>
            <div>
                <a href="#" class="me-3"><i class="bi bi-facebook fs-5"></i></a>
                <a href="#" class="me-3"><i class="bi bi-instagram fs-5"></i></a>
                <a href="#"><i class="bi bi-youtube fs-5"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-dismiss toasts
document.querySelectorAll('.toast').forEach(t => {
    setTimeout(() => { let toast = bootstrap.Toast.getInstance(t); if(toast) toast.hide(); else t.remove(); }, 4000);
});

// Update cart count
function updateCartCount(count) {
    document.getElementById('cart-count').textContent = count;
}

// Add to cart AJAX
document.querySelectorAll('.btn-them-gio-hang').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.dataset.url;
        const qty = this.closest('[data-qty]')?.dataset.qty || 1;
        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ so_luong: qty })
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) { updateCartCount(data.count); showToast(data.message, 'success'); }
            else showToast(data.message, 'danger');
        });
    });
});

function showToast(msg, type='success') {
    const div = document.createElement('div');
    div.className = `toast show align-items-center text-white bg-${type} border-0`;
    div.innerHTML = `<div class="d-flex"><div class="toast-body">${msg}</div><button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.querySelector('.toast-container').appendChild(div);
    setTimeout(() => div.remove(), 4000);
}
</script>
@stack('scripts')
</body>
</html>