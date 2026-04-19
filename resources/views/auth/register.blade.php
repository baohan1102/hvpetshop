{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title', 'Đăng ký - HV Pet Shop')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background:linear-gradient(135deg,#e0f7fa,#f8f9fa)">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="width:100%;max-width:500px">
        <div class="text-center mb-4">
            <i class="bi bi-paw-print-fill fs-1" style="color:var(--primary)"></i>
            <h3 class="fw-bold mt-2">Tạo tài khoản</h3>
            <p class="text-muted">Tham gia cộng đồng yêu thú cưng HV!</p>
        </div>
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Họ và tên *</label>
                    <input type="text" name="ho_ten" class="form-control" value="{{ old('ho_ten') }}" placeholder="Nguyễn Văn A" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Số điện thoại *</label>
                    <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai') }}" placeholder="0xxxxxxxxx" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@gmail.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mật khẩu *</label>
                    <input type="password" name="mat_khau" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Xác nhận mật khẩu *</label>
                    <input type="password" name="mat_khau_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">Đăng ký</button>
                </div>
            </div>
        </form>
        <div class="text-center mt-3">
            <span class="text-muted">Đã có tài khoản?</span>
            <a href="{{ route('login') }}" class="text-primary fw-semibold ms-1">Đăng nhập</a>
        </div>
    </div>
</div>
@endsection