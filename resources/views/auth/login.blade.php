{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
@section('title', 'Đăng nhập - HV Pet Shop')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background:linear-gradient(135deg,#e0f7fa,#f8f9fa)">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="width:100%;max-width:440px">
        <div class="text-center mb-4">
            <i class="bi bi-paw-print-fill fs-1" style="color:var(--primary)"></i>
            <h3 class="fw-bold mt-2">Đăng nhập</h3>
            <p class="text-muted">Chào mừng trở lại HV Pet Shop!</p>
        </div>
        @if($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Số điện thoại</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}" placeholder="0xxxxxxxxx" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="mat_khau" class="form-control" placeholder="••••••••" required id="pwInput">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePw()"><i class="bi bi-eye" id="eyeIcon"></i></button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                </div>
                <a href="{{ route('forgot-password') }}" class="text-primary small">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">Đăng nhập</button>
        </form>
        <div class="text-center mt-3">
            <span class="text-muted">Chưa có tài khoản?</span>
            <a href="{{ route('register') }}" class="text-primary fw-semibold ms-1">Đăng ký ngay</a>
        </div>
    </div>
</div>
@push('scripts')
<script>
function togglePw() {
    const inp = document.getElementById('pwInput');
    const icon = document.getElementById('eyeIcon');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endpush
@endsection