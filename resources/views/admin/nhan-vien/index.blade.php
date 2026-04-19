{{-- resources/views/admin/nhan-vien/index.blade.php --}}
@extends('layouts.admin')
@section('title','Nhân viên')
@section('page-title','Quản lý nhân viên')
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">➕ Thêm nhân viên mới</h6>
            <form action="{{ route('admin.nhan-vien.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Họ và tên *</label>
                    <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror" required>
                    @error('ho_ten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Số điện thoại * (dùng để đăng nhập)</label>
                    <input type="text" name="so_dien_thoai" class="form-control @error('so_dien_thoai') is-invalid @enderror" required placeholder="0xxxxxxxxx">
                    @error('so_dien_thoai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="alert alert-info py-2 small">
                    <i class="bi bi-info-circle me-1"></i>
                    Mật khẩu mặc định: <strong>1111</strong><br>
                    Nhân viên sẽ được yêu cầu đổi mật khẩu khi đăng nhập lần đầu.
                </div>
                <button class="btn btn-primary w-100 fw-bold">Thêm nhân viên</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="stat-card">
            <form class="d-flex gap-2 mb-3" method="GET">
                <input type="text" name="tu_khoa" class="form-control form-control-sm" style="max-width:250px" placeholder="Tìm theo tên, SĐT..." value="{{ request('tu_khoa') }}">
                <button class="btn btn-sm btn-outline-secondary">Tìm</button>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>Họ tên</th><th>SĐT</th><th>Email</th><th>Ngày tạo</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
                    <tbody>
                    @foreach($nhanViens as $nv)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $nv->ho_ten }}</div>
                            @if($nv->mat_khau_mac_dinh)<span class="badge bg-warning text-dark small">Chưa đổi MK</span>@endif
                        </td>
                        <td>{{ $nv->so_dien_thoai }}</td>
                        <td class="text-muted small">{{ $nv->email ?? '-' }}</td>
                        <td class="text-muted small">{{ $nv->created_at->format('d/m/Y') }}</td>
                        <td><span class="badge {{ $nv->trang_thai ? 'bg-success' : 'bg-danger' }}">{{ $nv->trang_thai ? 'Hoạt động' : 'Đã khóa' }}</span></td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <form action="{{ route('admin.nhan-vien.cap-lai-mat-khau', $nv->id) }}" method="POST" onsubmit="return confirm('Cấp lại mật khẩu 1111?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-warning" title="Cấp lại MK mặc định"><i class="bi bi-key"></i></button>
                                </form>
                                <form action="{{ route('admin.nhan-vien.toggle', $nv->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm {{ $nv->trang_thai ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $nv->trang_thai ? 'Khóa TK' : 'Mở khóa' }}">
                                        <i class="bi {{ $nv->trang_thai ? 'bi-lock' : 'bi-unlock' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $nhanViens->links() }}
        </div>
    </div>
</div>
@endsection