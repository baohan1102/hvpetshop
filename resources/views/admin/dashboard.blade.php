@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')

{{-- Stat Cards --}}
<div class="row g-4 mb-4">
    @foreach([
        ['icon'=>'bi-box-seam','bg'=>'#e3f2fd','ic_color'=>'#1565C0','label'=>'Sản phẩm','value'=>$tongSanPham,'sub'=>'Đang bán'],
        ['icon'=>'bi-bag-check','bg'=>'#fce4ec','ic_color'=>'#c62828','label'=>'Đơn hàng','value'=>$tongDonHang,'sub'=>$donHangMoi.' chờ xác nhận'],
        ['icon'=>'bi-currency-dollar','bg'=>'#e8f5e9','ic_color'=>'#2e7d32','label'=>'Doanh thu tháng','value'=>number_format($doanhThuThang).'đ','sub'=>date('m/Y')],
        ['icon'=>'bi-people','bg'=>'#f3e5f5','ic_color'=>'#6a1b9a','label'=>'Khách hàng','value'=>$tongKhachHang,'sub'=>$tongNhanVien.' nhân viên'],
    ] as $card)
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:{{ $card['bg'] }}">
                <i class="bi {{ $card['icon'] }}" style="color:{{ $card['ic_color'] }}"></i>
            </div>
            <div>
                <div class="text-muted small">{{ $card['label'] }}</div>
                <div class="fw-bold fs-4">{{ $card['value'] }}</div>
                <div class="text-muted" style="font-size:12px">{{ $card['sub'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    {{-- Biểu đồ doanh thu --}}
    <div class="col-lg-8">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">📈 Doanh thu 6 tháng gần nhất</h6>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

    {{-- Sản phẩm bán chạy --}}
    <div class="col-lg-4">
        <div class="stat-card h-100">
            <h6 class="fw-bold mb-3">🔥 Sản phẩm bán chạy</h6>
            @foreach($sanPhamBanChay as $sp)
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="text-muted" style="width:20px;font-size:12px">{{ $loop->iteration }}</span>
                <img src="{{ $sp->hinh_anh_url }}" alt="" style="width:36px;height:36px;object-fit:cover;border-radius:6px">
                <div class="flex-grow-1" style="overflow:hidden">
                    <div class="fw-semibold small text-truncate">{{ $sp->ten_sp }}</div>
                    <div class="text-muted" style="font-size:11px">{{ $sp->da_ban ?? 0 }} đã bán</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Đơn hàng gần đây --}}
    <div class="col-lg-8">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">📦 Đơn hàng gần đây</h6>
                <a href="{{ route('admin.don-hang.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Ngày đặt</th><th>Trạng thái</th></tr></thead>
                    <tbody>
                        @foreach($donHangsGanDay as $dh)
                        <tr>
                            <td><a href="{{ route('admin.don-hang.show', $dh->id) }}" class="fw-semibold text-primary">{{ $dh->ma_dh }}</a></td>
                            <td>{{ $dh->ho_ten_nhan }}</td>
                            <td class="fw-semibold">{{ number_format($dh->thanh_tien) }}đ</td>
                            <td class="text-muted small">{{ $dh->ngay_dat->format('d/m H:i') }}</td>
                            <td><span class="badge bg-{{ $dh->trangThaiLabel['class'] }}">{{ $dh->trangThaiLabel['label'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Cảnh báo tồn kho --}}
    <div class="col-lg-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3 text-danger">⚠️ Sản phẩm sắp hết hàng</h6>
            @forelse($sanPhamHetHang as $sp)
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-danger bg-opacity-10 rounded">
                <span class="small fw-semibold">{{ Str::limit($sp->ten_sp, 25) }}</span>
                <span class="badge bg-danger">{{ $sp->so_luong }} còn</span>
            </div>
            @empty
            <p class="text-muted small">Tất cả sản phẩm đang đủ hàng ✓</p>
            @endforelse
            <a href="{{ route('admin.kho.thong-ke') }}" class="btn btn-outline-warning btn-sm w-100 mt-2">Xem kho hàng</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($doanhThu6Thang, 'thang')) !!},
        datasets: [{
            label: 'Doanh thu (đ)',
            data: {!! json_encode(array_column($doanhThu6Thang, 'doanh_thu')) !!},
            backgroundColor: 'rgba(0,188,212,0.7)',
            borderColor: '#00BCD4',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => (v/1000000).toFixed(1) + 'M' } }
        }
    }
});
</script>
@endpush
@endsection