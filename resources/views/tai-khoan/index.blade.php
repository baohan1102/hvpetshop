@extends('layouts.app')
@section('title', 'Tài khoản của tôi - HV Pet Shop')
@section('content')
<div class="container py-5">
    <div class="row g-4">
        {{-- Sidebar --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 text-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width:70px;height:70px;font-size:28px">
                    {{ mb_substr($user->ho_ten, 0, 1) }}
                </div>
                <h6 class="fw-bold mb-1">{{ $user->ho_ten }}</h6>
                <div class="text-muted small">{{ $user->so_dien_thoai }}</div>
                @php $loai = $user->loaiKhachHang(); @endphp
                <span class="badge mt-2" style="background:{{ $loai['color'] }}">{{ $loai['ten'] }}</span>
            </div>
            <div class="list-group list-group-flush rounded-4 shadow-sm border-0">
                <a href="{{ route('tai-khoan.index') }}" class="list-group-item list-group-item-action border-0"><i class="bi bi-person me-2"></i>Thông tin tài khoản</a>
                <a href="{{ route('don-hang.lich-su') }}" class="list-group-item list-group-item-action border-0"><i class="bi bi-bag me-2"></i>Đơn hàng của tôi</a>
                <a href="{{ route('doi-mat-khau') }}" class="list-group-item list-group-item-action border-0"><i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="list-group-item list-group-item-action border-0 text-danger w-100 text-start"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                </form>
            </div>
        </div>

        {{-- Nội dung --}}
        <div class="col-md-9">
            {{-- Thông tin cá nhân --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-4">👤 Thông tin cá nhân</h5>
                <form action="{{ route('tai-khoan.cap-nhat') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ và tên *</label>
                            <input type="text" name="ho_ten" class="form-control" value="{{ $user->ho_ten }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại *</label>
                            <input type="text" name="so_dien_thoai" class="form-control" value="{{ $user->so_dien_thoai }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control" value="{{ $user->ngay_sinh?->format('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary fw-bold">Lưu thay đổi</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Địa chỉ --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">📍 Địa chỉ giao hàng</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddrModal">
                        <i class="bi bi-plus-lg me-1"></i>Thêm địa chỉ
                    </button>
                </div>
                @forelse($diaChis as $dc)
                <div class="border rounded-3 p-3 mb-3 {{ $dc->la_mac_dinh ? 'border-primary' : '' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="fw-semibold">{{ $dc->ho_ten }}</span>
                            @if($dc->la_mac_dinh) <span class="badge bg-primary ms-2">Mặc định</span> @endif
                            <br><span class="text-muted">{{ $dc->so_dien_thoai }}</span>
                            <br><span class="text-muted small">{{ $dc->dia_chi_day_du }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            @if(!$dc->la_mac_dinh)
                            <form action="{{ route('tai-khoan.dia-chi.mac-dinh', $dc->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="btn btn-outline-primary btn-sm">Đặt mặc định</button>
                            </form>
                            @endif
                            <form action="{{ route('tai-khoan.dia-chi.xoa', $dc->id) }}" method="POST" onsubmit="return confirm('Xóa địa chỉ này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">Chưa có địa chỉ nào. Thêm địa chỉ để thanh toán nhanh hơn!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal thêm địa chỉ --}}
<div class="modal fade" id="addAddrModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content rounded-4">
        <div class="modal-header border-0"><h5 class="modal-title fw-bold">Thêm địa chỉ mới</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <form action="{{ route('tai-khoan.dia-chi.them') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Họ và tên *</label>
                        <input type="text" name="ho_ten" class="form-control" required value="{{ auth()->user()->ho_ten }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Số điện thoại *</label>
                        <input type="text" name="so_dien_thoai" class="form-control" required value="{{ auth()->user()->so_dien_thoai }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Địa chỉ chi tiết *</label>
                        <input type="text" name="dia_chi_chi_tiet" class="form-control" placeholder="Số nhà, tên đường" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Quận/Huyện *</label>
                        <input type="text" name="quan_huyen" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tỉnh/Thành phố *</label>
                        <input type="text" name="tinh_thanh" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="la_mac_dinh" id="defAddr">
                            <label class="form-check-label" for="defAddr">Đặt làm địa chỉ mặc định</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary fw-bold">Lưu địa chỉ</button>
            </div>
        </form>
    </div></div>
</div>
@endsection