{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.app')
@section('title', 'Quên mật khẩu')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background:linear-gradient(135deg,#e0f7fa,#f8f9fa)">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="width:100%;max-width:440px">
        <div class="text-center mb-4">
            <i class="bi bi-key-fill fs-1 text-warning"></i>
            <h3 class="fw-bold mt-2">Quên mật khẩu</h3>
            <p class="text-muted">Nhập email để nhận link đặt lại mật khẩu</p>
        </div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold">Địa chỉ Email</label>
                <input type="email" name="email" class="form-control form-control-lg" placeholder="email@gmail.com" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">Gửi link đặt lại mật khẩu</button>
        </form>
        <div class="text-center mt-3"><a href="{{ route('login') }}" class="text-primary"><i class="bi bi-arrow-left me-1"></i>Quay lại đăng nhập</a></div>
    </div>
</div>
@endsection