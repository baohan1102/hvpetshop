@extends('layouts.app')
@section('title', 'Lịch sử mua hàng - HV Pet Shop')
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">📦 Đơn hàng của tôi</h2>

    {{-- Tabs trạng thái --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        @foreach([''=>'Tất cả','cho_xac_nhan'=>'Chờ xác nhận','da_xac_nhan'=>'Đã xác nhận','dang_giao'=>'Đang giao','da_hoan_thanh'=>'Đã hoàn thành','da_huy'=>'Đã hủy'] as $val=>$label)
        <a href="{{ route('don-hang.lich-su', $val ? ['trang_thai'=>$val] : []) }}"
           class="btn btn-sm {{ request('trang_thai')===$val ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @forelse($donHangs as $dh)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-light border-0 rounded-top-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Mã đơn: {{ $dh->ma_dh }}</strong>
                <span class="text-muted ms-2 small">{{ $dh->ngay_dat->format('d/m/Y H:i') }}</span>
            </div>
            <span class="badge bg-{{ $dh->trangThaiLabel['class'] }} fs-6">{{ $dh->trangThaiLabel['label'] }}</span>
        </div>
        <div class="card-body">
            @foreach($dh->chiTiets->take(2) as $ct)
            <div class="d-flex gap-3 align-items-center mb-2">
                <img src="{{ $ct->sanPham->hinh_anh_url }}" alt="" style="width:55px;height:55px;object-fit:cover;border-radius:8px">
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $ct->sanPham->ten_sp }}</div>
                    <small class="text-muted">x{{ $ct->so_luong }} &times; {{ number_format($ct->gia) }}đ</small>
                </div>
                <div class="fw-bold" style="color:var(--primary)">{{ number_format($ct->thanh_tien) }}đ</div>
            </div>
            @endforeach
            @if($dh->chiTiets->count() > 2)
            <p class="text-muted small">...và {{ $dh->chiTiets->count() - 2 }} sản phẩm khác</p>
            @endif
        </div>
        <div class="card-footer bg-white border-0 rounded-bottom-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="text-muted">Tổng thanh toán:</span>
                <strong class="fs-5 ms-1" style="color:var(--primary)">{{ number_format($dh->thanh_tien) }}đ</strong>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('don-hang.chi-tiet', $dh->id) }}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                @if($dh->canHuy())
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#huyModal{{ $dh->id }}">Hủy đơn</button>
                @endif
                @if($dh->canNhanHang())
                <form action="{{ route('don-hang.nhan-hang', $dh->id) }}" method="POST" onsubmit="return confirm('Xác nhận đã nhận được hàng?')">
                    @csrf
                    <button class="btn btn-success btn-sm fw-bold">✓ Đã nhận được hàng</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal hủy đơn --}}
    @if($dh->canHuy())
    <div class="modal fade" id="huyModal{{ $dh->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Hủy đơn hàng {{ $dh->ma_dh }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button></div>
                <form action="{{ route('don-hang.huy', $dh->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label fw-semibold">Lý do hủy *</label>
                        <select name="ly_do" class="form-select" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                            <option value="Đặt trùng đơn">Đặt trùng đơn</option>
                            <option value="Muốn thay đổi địa chỉ giao hàng">Muốn thay đổi địa chỉ giao hàng</option>
                            <option value="Tìm thấy giá rẻ hơn">Tìm thấy giá rẻ hơn</option>
                            <option value="Không có nhu cầu nữa">Không có nhu cầu nữa</option>
                            <option value="Lý do khác">Lý do khác</option>
                        </select>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @empty
    <div class="text-center py-5">
        <i class="bi bi-bag display-1 text-muted"></i>
        <h4 class="mt-3 text-muted">Chưa có đơn hàng nào</h4>
        <a href="{{ route('san-pham.danh-sach') }}" class="btn btn-primary mt-3">Mua sắm ngay</a>
    </div>
    @endforelse

    {{ $donHangs->links() }}
</div>
@endsection