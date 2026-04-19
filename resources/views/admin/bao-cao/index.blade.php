{{-- resources/views/admin/bao-cao/index.blade.php --}}
@extends('layouts.admin')
@section('title','Báo cáo')
@section('page-title','Báo cáo doanh thu')
@section('content')
<div class="d-flex gap-2 mb-4">
    @foreach(['ngay'=>'Theo ngày','tuan'=>'Theo tuần','thang'=>'Theo tháng'] as $v=>$l)
    <a href="{{ route('admin.bao-cao.index', ['kieu'=>$v]) }}" class="btn {{ $kieu===$v ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $l }}</a>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="text-muted small mb-1">Tổng doanh thu</div>
            <div class="fw-bold fs-3" style="color:var(--primary)">{{ number_format($tongDoanhThu) }}đ</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">📈 Biểu đồ doanh thu</h6>
            <canvas id="bcChart" height="120"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="stat-card h-100">
            <h6 class="fw-bold mb-3">📊 Bảng số liệu</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Thời gian</th><th class="text-end">Doanh thu</th></tr></thead>
                    <tbody>
                    @foreach($labels as $i=>$label)
                    <tr><td>{{ $label }}</td><td class="text-end fw-semibold">{{ number_format($data[$i]) }}đ</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">🔥 Hàng hóa bán chạy</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead><tr><th>#</th><th>Sản phẩm</th><th class="text-end">Đã bán</th></tr></thead>
                    <tbody>
                    @foreach($hangHoaBanChay as $sp)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td><img src="{{ $sp->hinh_anh_url }}" alt="" style="width:32px;height:32px;object-fit:cover;border-radius:4px;margin-right:8px">{{ Str::limit($sp->ten_sp, 30) }}</td>
                        <td class="text-end fw-bold">{{ $sp->da_ban ?? 0 }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">💎 Khách hàng doanh thu cao nhất</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead><tr><th>#</th><th>Khách hàng</th><th class="text-end">Tổng chi tiêu</th></tr></thead>
                    <tbody>
                    @foreach($khachHangDoanhThu as $kh)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold">{{ $kh->ho_ten }}</div>
                            <small class="text-muted">{{ $kh->so_dien_thoai }}</small>
                        </td>
                        <td class="text-end fw-bold" style="color:var(--primary)">{{ number_format($kh->tong_chi_tieu ?? 0) }}đ</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('bcChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
            label: 'Doanh thu',
            data: {!! json_encode($data) !!},
            borderColor: '#00BCD4',
            backgroundColor: 'rgba(0,188,212,0.1)',
            tension: 0.4, fill: true, pointRadius: 5, pointBackgroundColor: '#00BCD4'
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { ticks: { callback: v => (v/1000000).toFixed(1)+'M' } } } }
});
</script>
@endpush
@endsection