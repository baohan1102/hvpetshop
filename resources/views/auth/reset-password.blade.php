{{-- resources/views/auth/reset-password.blade.php --}}
@extends('layouts.app')
@section('title', 'Đặt lại mật khẩu')
@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="width:100%;max-width:440px">
        <h3 class="fw-bold mb-4">Đặt lại mật khẩu</h3>
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <form action="/reset-password/{{ $token }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Mật khẩu mới *</label>
                <input type="password" name="mat_khau" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Xác nhận mật khẩu *</label>
                <input type="password" name="mat_khau_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">Đặt lại mật khẩu</button>
        </form>
    </div>
</div>
@endsection