@extends('layouts.app')
@section('title', 'Thanh toán - HV Pet Shop')
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-bag-check me-2" style="color:var(--primary)"></i>Thanh toán đơn hàng</h2>

    <form action="{{ route('don-hang.dat-hang') }}" method="POST" id="checkoutForm">
        @csrf
        @if(request('khuyen_mai_id'))
        <input type="hidden" name="khuyen_mai_id" value="{{ request('khuyen_mai_id') }}">
        @endif

        <div class="row g-4">
            {{-- THÔNG TIN GIAO HÀNG --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-4">📍 Thông tin giao hàng</h5>

                    @if($diaChis->isNotEmpty())
                    <div class="mb-3">
                        <label class="fw-semibold mb-2">Chọn địa chỉ đã lưu</label>
                        @foreach($diaChis as $dc)
                        <div class="form-check border rounded-3 p-3 mb-2 {{ $dc->la_mac_dinh ? 'border-primary' : '' }}">
                            <input class="form-check-input" type="radio" name="saved_address" id="addr{{ $dc->id }}"
                                value="{{ $dc->id }}" {{ $dc->la_mac_dinh ? 'checked' : '' }}
                                data-ten="{{ $dc->ho_ten }}" data-sdt="{{ $dc->so_dien_thoai }}"
                                data-diachi="{{ $dc->dia_chi_day_du }}">
                            <label class="form-check-label w-100" for="addr{{ $dc->id }}">
                                <strong>{{ $dc->ho_ten }}</strong> - {{ $dc->so_dien_thoai }}
                                <br><small class="text-muted">{{ $dc->dia_chi_day_du }}</small>
                                @if($dc->la_mac_dinh) <span class="badge bg-primary ms-2">Mặc định</span> @endif
                            </label>
                        </div>
                        @endforeach
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="saved_address" id="addrNew" value="new">
                            <label class="form-check-label" for="addrNew">+ Nhập địa chỉ mới</label>
                        </div>
                    </div>
                    @endif

                    <div id="manualAddressForm" style="{{ $diaChis->isEmpty() ? '' : 'display:none' }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ tên người nhận *</label>
                                <input type="text" id="ho_ten_nhan_input" name="ho_ten_nhan"
    class="form-control @error('ho_ten_nhan') is-invalid @enderror"
    value="{{ old('ho_ten_nhan', auth()->user()->ho_ten) }}" required>

                                @error('ho_ten_nhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại *</label>
                                <input type="text" id="sdt_nhan_input" name="so_dien_thoai_nhan"
    class="form-control @error('so_dien_thoai_nhan') is-invalid @enderror"
    value="{{ old('so_dien_thoai_nhan', auth()->user()->so_dien_thoai) }}" required>
                                @error('so_dien_thoai_nhan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Địa chỉ giao hàng *</label>
                               <input type="text" id="dia_chi_input" name="dia_chi_giao"
    class="form-control @error('dia_chi_giao') is-invalid @enderror"
    value="{{ old('dia_chi_giao') }}" required>
                                @error('dia_chi_giao')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Hidden inputs for saved address --}}
                   <input type="hidden" id="ho_ten_nhan_hidden" value="{{ $diaChiMacDinh?->ho_ten ?? '' }}">
<input type="hidden" id="sdt_nhan_hidden" value="{{ $diaChiMacDinh?->so_dien_thoai ?? '' }}">
<input type="hidden" id="diachi_hidden" value="{{ $diaChiMacDinh?->dia_chi_day_du ?? '' }}">
                </div>

                {{-- PHƯƠNG THỨC THANH TOÁN --}}
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">💳 Phương thức thanh toán</h5>
                    <div class="row g-3">
@foreach([
    'cod'          => ['🚚', 'COD', 'Trả tiền khi nhận hàng'],
    'chuyen_khoan' => ['🏦', 'Chuyển khoản', 'Thanh toán qua ngân hàng'],
    'vnpay'        => ['🏧', 'VNPAY', 'Thanh toán cổng VNPAY'],
    'momo'         => ['📱', 'Ví MoMo', 'Thanh toán qua ví MoMo'],
] as $val => [$icon, $name, $desc])                
        <div class="col-6">
                            <div class="form-check border rounded-3 p-3 h-100 payment-option {{ $val === 'cod' ? 'border-primary' : '' }}" style="cursor:pointer">
                                <input class="form-check-input" type="radio" name="phuong_thuc_tt" id="pt{{ $val }}" value="{{ $val }}" {{ $val === 'cod' ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="pt{{ $val }}" style="cursor:pointer">
                                    <div class="fs-4">{{ $icon }}</div>
                                    <div class="fw-semibold">{{ $name }}</div>
                                    <div class="small text-muted">{{ $desc }}</div>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- TÓM TẮT ĐƠN HÀNG --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top:80px">
                    <h5 class="fw-bold mb-4">📋 Tóm tắt đơn hàng</h5>

                    @foreach($gioHangs as $item)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <img src="{{ $item->sanPham->hinh_anh_url }}" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">{{ $item->sanPham->ten_sp }}</div>
                            <div class="text-muted small">x{{ $item->so_luong }}</div>
                        </div>
                        <div class="fw-bold" style="color:var(--primary)">{{ number_format($item->so_luong * $item->gia) }}đ</div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính</span>
                        <span>{{ number_format($tongTien) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span>{{ number_format($phiShip) }}đ</span>
                    </div>

                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-calendar me-2"></i>
                        <strong>Ngày đặt:</strong> {{ now()->format('d/m/Y H:i') }}<br>
                        <i class="bi bi-truck me-2"></i>
                        <strong>Giao hàng dự kiến:</strong> {{ now()->addDays(3)->format('d/m/Y') }}
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-5">Tổng cộng</span>
                        <span class="fw-bold fs-4" style="color:var(--primary)">{{ number_format($tongTien + $phiShip) }}đ</span>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">
                        <i class="bi bi-bag-check me-2"></i>Đặt hàng ngay
                    </button>

                    <div class="text-center mt-3 small text-muted">
                        Bằng cách đặt hàng, bạn đồng ý với <a href="#">Điều khoản dịch vụ</a> của chúng tôi.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
const radios = document.querySelectorAll('input[name="saved_address"]');

function toggleForm(isNew) {
    const form = document.getElementById('manualAddressForm');

    const hoTen = document.getElementById('ho_ten_nhan_input');
    const sdt = document.getElementById('sdt_nhan_input');
    const diaChi = document.getElementById('dia_chi_input');

    if (isNew) {
        form.style.display = '';

        // cho nhập
        hoTen.readOnly = false;
        sdt.readOnly = false;
        diaChi.readOnly = false;

    } else {
        form.style.display = 'none';

        // ❗ KHÔNG disable, chỉ readonly
        hoTen.readOnly = true;
        sdt.readOnly = true;
        diaChi.readOnly = true;
    }
}

// change radio
radios.forEach(radio => {
    radio.addEventListener('change', function() {

        if (this.value === 'new') {
            toggleForm(true);
        } else {
            toggleForm(false);

            // gán dữ liệu
            document.getElementById('ho_ten_nhan_input').value = this.dataset.ten;
            document.getElementById('sdt_nhan_input').value = this.dataset.sdt;
            document.getElementById('dia_chi_input').value = this.dataset.diachi;
        }
    });
});

// load ban đầu
const checked = document.querySelector('input[name="saved_address"]:checked');
if (checked && checked.value !== 'new') {
    toggleForm(false);

    // gán luôn dữ liệu mặc định
    document.getElementById('ho_ten_nhan_input').value = checked.dataset.ten;
    document.getElementById('sdt_nhan_input').value = checked.dataset.sdt;
    document.getElementById('dia_chi_input').value = checked.dataset.diachi;
} else {
    toggleForm(true);
}
</script>
@endpush
@endsection