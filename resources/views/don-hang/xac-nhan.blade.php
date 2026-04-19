{{-- resources/views/don-hang/xac-nhan.blade.php --}}
@extends('layouts.app')
@section('title', 'Đặt hàng thành công - HV Pet Shop')
@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <div class="display-1 mb-3">🎉</div>
        <h2 class="fw-bold text-success">Đặt hàng thành công!</h2>
        <p class="text-muted fs-5">Cảm ơn bạn đã mua sắm tại HV Pet Shop</p>
        <div class="badge bg-light text-dark border fs-6 p-3">Mã đơn hàng: <strong class="text-primary">{{ $donHang->ma_dh }}</strong></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-4">📦 Chi tiết đơn hàng</h5>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="text-muted small">Ngày đặt hàng</div>
                        <div class="fw-semibold">{{ $donHang->ngay_dat->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Giao hàng dự kiến</div>
                        <div class="fw-semibold">{{ $donHang->ngay_giao_du_kien?->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Địa chỉ nhận</div>
                        <div class="fw-semibold">{{ $donHang->ho_ten_nhan }}</div>
                        <div class="small text-muted">{{ $donHang->so_dien_thoai_nhan }}</div>
                        <div class="small text-muted">{{ $donHang->dia_chi_giao }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Phương thức thanh toán</div>
                        <div class="fw-semibold">{{ strtoupper($donHang->phuong_thuc_tt) }}</div>
                    </div>
                </div>

                @foreach($donHang->chiTiets as $ct)
                <div class="d-flex gap-3 mb-2 pb-2 border-bottom">
                    <img src="{{ $ct->sanPham->hinh_anh_url }}" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:6px">
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">{{ $ct->sanPham->ten_sp }}</div>
                        <div class="text-muted small">x{{ $ct->so_luong }}</div>
                    </div>
                    <div class="fw-bold small">{{ number_format($ct->thanh_tien) }}đ</div>
                </div>
                @endforeach

                <div class="mt-3">
                    <div class="d-flex justify-content-between text-muted small"><span>Tạm tính</span><span>{{ number_format($donHang->tong_tien) }}đ</span></div>
                    <div class="d-flex justify-content-between text-muted small"><span>Phí vận chuyển</span><span>{{ $donHang->phi_van_chuyen > 0 ? number_format($donHang->phi_van_chuyen).'đ' : 'Miễn phí' }}</span></div>
                    @if($donHang->tien_giam > 0)
                    <div class="d-flex justify-content-between text-success small"><span>Giảm giá</span><span>-{{ number_format($donHang->tien_giam) }}đ</span></div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                        <span>Tổng thanh toán</span>
                        <span style="color:var(--primary)">{{ number_format($donHang->thanh_tien) }}đ</span>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('don-hang.lich-su') }}" class="btn btn-outline-primary btn-lg">Xem đơn hàng của tôi</a>
                <a href="{{ route('san-pham.danh-sach') }}" class="btn btn-primary btn-lg">Tiếp tục mua sắm</a>
            </div>
        </div>
    </div>
</div>
@endsection