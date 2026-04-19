{{-- resources/views/admin/khach-hang/show.blade.php --}}
@extends('layouts.admin')
@section('title','Chi tiết khách hàng')
@section('page-title','Chi tiết khách hàng')
@section('content')
@php $loai = $kh->loaiKhachHang(); @endphp
<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card text-center mb-4">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px;font-size:32px">{{ mb_substr($kh->ho_ten,0,1) }}</div>
            <h5 class="fw-bold">{{ $kh->ho_ten }}</h5>
            <p class="text-muted mb-2">{{ $kh->so_dien_thoai }}</p>
            <span class="badge fs-6" style="background:{{ $loai['color'] }}">{{ $loai['ten'] }}</span>
            <hr>
            <div class="text-muted small">Ngày đăng ký</div>
            <div class="fw-semibold">{{ $kh->created_at->format('d/m/Y') }}</div>
            <div class="text-muted small mt-2">Email</div>
            <div>{{ $kh->email ?? 'Chưa cập nhật' }}</div>
            <div class="text-muted small mt-2">Tổng chi tiêu</div>
            <div class="fw-bold fs-5" style="color:var(--primary)">{{ number_format($kh->tongChiTieu()) }}đ</div>
        </div>
        <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-left me-2"></i>Quay lại</a>
    </div>
    <div class="col-md-8">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">📦 Lịch sử đặt hàng</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>Mã đơn</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Trạng thái</th><th></th></tr></thead>
                    <tbody>
                    @foreach($donHangs as $dh)
                    <tr>
                        <td class="fw-semibold text-primary">{{ $dh->ma_dh }}</td>
                        <td class="small text-muted">{{ $dh->ngay_dat->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold">{{ number_format($dh->thanh_tien) }}đ</td>
                        <td><span class="badge bg-{{ $dh->trangThaiLabel['class'] }}">{{ $dh->trangThaiLabel['label'] }}</span></td>
                        <td><a href="{{ route('admin.don-hang.show', $dh->id) }}" class="btn btn-sm btn-outline-primary">Xem</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $donHangs->links() }}
        </div>
    </div>
</div>
@endsection