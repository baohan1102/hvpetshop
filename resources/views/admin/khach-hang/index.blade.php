{{-- resources/views/admin/khach-hang/index.blade.php --}}
@extends('layouts.admin')
@section('title','Khách hàng')
@section('page-title','Quản lý & Doanh thu khách hàng')
@section('content')
<div class="stat-card mb-4">
    <form class="d-flex gap-2 mb-3" method="GET">
        <input type="text" name="tu_khoa" class="form-control" style="max-width:250px" placeholder="Tìm tên, SĐT..." value="{{ request('tu_khoa') }}">
        <button class="btn btn-outline-secondary btn-sm">Tìm</button>
    </form>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Khách hàng</th><th>SĐT</th><th>Email</th><th>Phân loại</th><th>Số đơn</th><th>Tổng chi tiêu</th><th>Thao tác</th></tr></thead>
            <tbody>
            @foreach($khachHangs as $kh)
            @php $loai = $kh->loaiKhachHang(); @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <div class="fw-semibold">{{ $kh->ho_ten }}</div>
                    <small class="text-muted">Đăng ký: {{ $kh->created_at->format('d/m/Y') }}</small>
                </td>
                <td>{{ $kh->so_dien_thoai }}</td>
                <td class="text-muted small">{{ $kh->email ?? '-' }}</td>
                <td><span class="badge" style="background:{{ $loai['color'] }}">{{ $loai['ten'] }}</span></td>
                <td>{{ $kh->so_don ?? 0 }}</td>
                <td class="fw-bold" style="color:var(--primary)">{{ number_format($kh->tong_chi_tieu ?? 0) }}đ</td>
                <td><a href="{{ route('admin.khach-hang.show', $kh->id) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $khachHangs->links() }}
</div>

{{-- Chú thích phân loại --}}
<div class="stat-card">
    <h6 class="fw-bold mb-3">📊 Phân loại khách hàng</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="border rounded-3 p-3 text-center" style="border-color:gold!important">
                <div class="fw-bold" style="color:gold;font-size:24px">🥇</div>
                <div class="fw-bold">Khách hàng Vàng</div>
                <div class="text-muted small">Chi tiêu ≥ 5.000.000đ</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border rounded-3 p-3 text-center" style="border-color:silver!important">
                <div class="fw-bold" style="color:silver;font-size:24px">🥈</div>
                <div class="fw-bold">Khách hàng Bạc</div>
                <div class="text-muted small">Chi tiêu ≥ 2.000.000đ</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border rounded-3 p-3 text-center" style="border-color:#cd7f32!important">
                <div class="fw-bold" style="color:#cd7f32;font-size:24px">🥉</div>
                <div class="fw-bold">Khách hàng Đồng</div>
                <div class="text-muted small">Chi tiêu &lt; 2.000.000đ</div>
            </div>
        </div>
    </div>
</div>
@endsection