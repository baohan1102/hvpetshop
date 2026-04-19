{{-- resources/views/admin/danh-muc/index.blade.php --}}
@extends('layouts.admin')
@section('title','Danh mục')
@section('page-title','Quản lý danh mục')
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">Thêm danh mục mới</h6>
            <form action="{{ route('admin.danh-muc.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên danh mục *</label>
                    <input type="text" name="ten_danh_muc" class="form-control" required placeholder="VD: Thức ăn cho Chó">
                </div>
                <button class="btn btn-primary w-100">Thêm danh mục</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="stat-card">
            <table class="table table-hover align-middle">
                <thead><tr><th>#</th><th>Tên danh mục</th><th>Số SP</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
                <tbody>
                @foreach($danhMucs as $dm)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <form action="{{ route('admin.danh-muc.update', $dm->id) }}" method="POST" class="d-flex gap-2">
                            @csrf @method('PUT')
                            <input type="text" name="ten_danh_muc" class="form-control form-control-sm" value="{{ $dm->ten_danh_muc }}">
                            <button class="btn btn-sm btn-outline-primary">Lưu</button>
                        </form>
                    </td>
                    <td>{{ $dm->sanPhams()->count() }}</td>
                    <td><span class="badge {{ $dm->trang_thai ? 'bg-success' : 'bg-secondary' }}">{{ $dm->trang_thai ? 'Hiển thị' : 'Đã ẩn' }}</span></td>
                    <td>
                        <form action="{{ route('admin.danh-muc.toggle', $dm->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm {{ $dm->trang_thai ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                {{ $dm->trang_thai ? 'Ẩn' : 'Hiện' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            {{ $danhMucs->links() }}
        </div>
    </div>
</div>
@endsection