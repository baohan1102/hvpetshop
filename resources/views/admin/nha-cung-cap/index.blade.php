{{-- resources/views/admin/nha-cung-cap/index.blade.php --}}
@extends('layouts.admin')
@section('title','Nhà cung cấp')
@section('page-title','Quản lý nhà cung cấp')
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.nha-cung-cap.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Thêm NCC</a>
</div>
<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Tên nhà cung cấp</th><th>Điện thoại</th><th>Email</th><th>Địa chỉ</th><th>Số SP</th><th>Thao tác</th></tr></thead>
            <tbody>
            @foreach($nhaCungCaps as $ncc)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="fw-semibold">{{ $ncc->ten_ncc }}</td>
                <td>{{ $ncc->so_dien_thoai ?? '-' }}</td>
                <td class="text-muted small">{{ $ncc->email ?? '-' }}</td>
                <td class="text-muted small">{{ $ncc->dia_chi ?? '-' }}</td>
                <td>{{ $ncc->sanPhams()->count() }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.nha-cung-cap.edit', $ncc->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.nha-cung-cap.destroy', $ncc->id) }}" method="POST" onsubmit="return confirm('Xóa nhà cung cấp này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $nhaCungCaps->links() }}
</div>
@endsection