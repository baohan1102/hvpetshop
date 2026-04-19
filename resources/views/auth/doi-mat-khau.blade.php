{{-- resources/views/auth/doi-mat-khau.blade.php --}}
@extends('layouts.app')
@section('title', 'Đổi mật khẩu')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h4 class="fw-bold mb-4"><i class="bi bi-shield-lock me-2" style="color:var(--primary)"></i>Đổi mật khẩu</h4>
                @if(session('warning'))<div class="alert alert-warning">{{ session('warning') }}</div>@endif
                @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                <form action="{{ route('doi-mat-khau') }}" method="POST">
                    @csrf @method('POST')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu hiện tại *</label>
                        <input type="password" name="mat_khau_cu" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu mới *</label>
                        <input type="password" name="mat_khau_moi" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Xác nhận mật khẩu mới *</label>
                        <input type="password" name="mat_khau_moi_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection