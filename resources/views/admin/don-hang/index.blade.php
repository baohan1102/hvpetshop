{{-- resources/views/admin/don-hang/index.blade.php --}}
@extends('layouts.admin')
@section('title','Quản lý đơn hàng')
@section('page-title','Quản lý đơn hàng')
@section('content')
<div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
    <form class="d-flex gap-2 flex-wrap flex-grow-1" method="GET">
        <input type="text" name="tu_khoa" class="form-control" style="max-width:200px" placeholder="Mã đơn, tên KH..." value="{{ request('tu_khoa') }}">
        <select name="trang_thai" class="form-select" style="max-width:180px">
            <option value="">Tất cả trạng thái</option>
            @foreach(['cho_xac_nhan'=>'Chờ xác nhận','da_xac_nhan'=>'Đã xác nhận','dang_giao'=>'Đang giao','da_hoan_thanh'=>'Đã hoàn thành','da_huy'=>'Đã hủy'] as $v=>$l)
            <option value="{{ $v }}" {{ request('trang_thai')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
        <input type="date" name="ngay_tu" class="form-control" style="max-width:140px" value="{{ request('ngay_tu') }}" placeholder="Từ ngày">
        <input type="date" name="ngay_den" class="form-control" style="max-width:140px" value="{{ request('ngay_den') }}" placeholder="Đến ngày">
        <button class="btn btn-outline-secondary">Lọc</button>
    </form>
</div>
<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Ngày đặt</th><th>Ngày giao DK</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
            <tbody>
            @foreach($donHangs as $dh)
            <tr>
                <td class="text-muted">{{ $loop->iteration }}</td>
                <td><a href="{{ route('admin.don-hang.show', $dh->id) }}" class="fw-semibold text-primary">{{ $dh->ma_dh }}</a></td>
                <td>
                    <div class="fw-semibold">{{ $dh->ho_ten_nhan }}</div>
                    <small class="text-muted">{{ $dh->so_dien_thoai_nhan }}</small>
                </td>
                <td class="fw-semibold" style="color:var(--primary)">{{ number_format($dh->thanh_tien) }}đ</td>
                <td class="small text-muted">{{ $dh->ngay_dat->format('d/m/Y H:i') }}</td>
                <td class="small">{{ $dh->ngay_giao_du_kien?->format('d/m/Y') ?? '-' }}</td>
                <td><span class="badge bg-{{ $dh->trangThaiLabel['class'] }}">{{ $dh->trangThaiLabel['label'] }}</span></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.don-hang.show', $dh->id) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $donHangs->links() }}
</div>
@endsection