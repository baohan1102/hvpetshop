<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - HV Pet Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary: #00BCD4; --sidebar-width: 250px; }
        body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: #1a1a2e;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: .3s;
            overflow-x: hidden;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: #1a1a2e; }
        .sidebar::-webkit-scrollbar-thumb { background: #ffffff30; border-radius: 2px; }
        .sidebar .brand { padding: 20px; border-bottom: 1px solid #ffffff20; flex-shrink: 0; }
        .sidebar .nav-link { color: #adb5bd; padding: 10px 20px; border-radius: 8px; margin: 2px 10px; display: flex; align-items: center; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: var(--primary); }
        .sidebar .nav-link i { width: 22px; }
        .sidebar .nav-section { color: #6c757d; font-size: 11px; text-transform: uppercase; padding: 12px 20px 4px; letter-spacing: 1px; }
        .sidebar nav { flex: 1; overflow-y: auto; overflow-x: hidden; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .topbar { background: #fff; padding: 12px 24px; box-shadow: 0 2px 4px rgba(0,0,0,.05); position: sticky; top: 0; z-index: 999; }
        .stat-card { border: none; border-radius: 16px; padding: 24px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .table th { font-weight: 600; font-size: 13px; text-transform: uppercase; color: #6c757d; }
        @media(max-width:768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .sidebar.show { transform: translateX(0); }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="brand">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center gap-2">
            <i class="bi bi-paw-print-fill fs-4" style="color:var(--primary)"></i>
            <span class="text-white fw-bold fs-5">HV Pet Shop</span>
        </a>
        <div class="text-muted small mt-1">{{ auth()->user()->vai_tro === 'chu_cua_hang' ? 'Chủ cửa hàng' : 'Nhân viên' }}</div>
    </div>

    <nav class="mt-2 pb-4">
        <div class="nav-section">Tổng quan</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>

        <div class="nav-section">Sản phẩm</div>
        <a href="{{ route('admin.san-pham.index') }}" class="nav-link {{ request()->routeIs('admin.san-pham*') ? 'active' : '' }}">
            <i class="bi bi-box-seam me-2"></i>Sản phẩm
        </a>
        <a href="{{ route('admin.danh-muc.index') }}" class="nav-link {{ request()->routeIs('admin.danh-muc*') ? 'active' : '' }}">
            <i class="bi bi-grid me-2"></i>Danh mục
        </a>

        <div class="nav-section">Kinh doanh</div>
        <a href="{{ route('admin.don-hang.index') }}" class="nav-link {{ request()->routeIs('admin.don-hang*') ? 'active' : '' }}">
            <i class="bi bi-bag me-2"></i>Đơn hàng
            @php $dh = \App\Models\DonHang::where('trang_thai','cho_xac_nhan')->count(); @endphp
            @if($dh > 0)<span class="badge rounded-pill ms-2" style="background:#f44336">{{ $dh }}</span>@endif
        </a>
        <a href="{{ route('admin.khuyen-mai.index') }}" class="nav-link {{ request()->routeIs('admin.khuyen-mai*') ? 'active' : '' }}">
            <i class="bi bi-tag me-2"></i>Khuyến mãi
        </a>

        @if(auth()->user()->isChuCuaHang())
        <div class="nav-section">Quản lý</div>
        <a href="{{ route('admin.kho.index') }}" class="nav-link {{ request()->routeIs('admin.kho*') ? 'active' : '' }}">
            <i class="bi bi-building me-2"></i>Kho hàng
        </a>
        <a href="{{ route('admin.nhan-vien.index') }}" class="nav-link {{ request()->routeIs('admin.nhan-vien*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i>Nhân viên
        </a>
        <a href="{{ route('admin.khach-hang.index') }}" class="nav-link {{ request()->routeIs('admin.khach-hang*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill me-2"></i>Khách hàng
        </a>
        <a href="{{ route('admin.nha-cung-cap.index') }}" class="nav-link {{ request()->routeIs('admin.nha-cung-cap*') ? 'active' : '' }}">
            <i class="bi bi-truck me-2"></i>Nhà cung cấp
        </a>
        <a href="{{ route('admin.bao-cao.index') }}" class="nav-link {{ request()->routeIs('admin.bao-cao*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart me-2"></i>Báo cáo
        </a>
        @endif

        <div class="nav-section">Tài khoản</div>
        <a href="{{ route('home') }}" class="nav-link">
            <i class="bi bi-house me-2"></i>Trang chủ
        </a>
        <form action="{{ route('logout') }}" method="POST" class="px-3 mt-2 mb-3">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
            </button>
        </form>
    </nav>
</div>

<div class="main-content">
    <div class="topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.don-hang.index') }}" class="text-decoration-none position-relative">
                <i class="bi bi-bell fs-5 text-muted"></i>
                @if(isset($dh) && $dh > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px">{{ $dh }}</span>
                @endif
            </a>
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i>{{ Str::limit(auth()->user()->ho_ten, 12) }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('tai-khoan.index') }}">Hồ sơ</a></li>
                    <li><a class="dropdown-item" href="{{ route('doi-mat-khau') }}">Đổi mật khẩu</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="p-4">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>