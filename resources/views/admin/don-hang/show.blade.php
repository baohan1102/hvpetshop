@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Chi tiết đơn hàng #' . $donHang->ma_dh)

@section('content')
<div class="container-fluid"> <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4"> <div class="card-body">
                    <h6 class="fw-bold mb-3">📦 Sản phẩm</h6>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donHang->chiTiets as $ct)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $ct->sanPham->hinh_anh_url }}" alt="" style="width:45px;height:45px;object-fit:cover;border-radius:6px">
                                            <span class="fw-semibold small">{{ $ct->sanPham->ten_sp }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($ct->gia) }}đ</td>
                                    <td class="text-center">{{ $ct->so_luong }}</td>
                                    <td class="fw-bold text-end" style="color:var(--primary)">{{ number_format($ct->thanh_tien) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-semibold">
                                <tr>
                                    <td colspan="3" class="text-end">Tạm tính:</td>
                                    <td class="text-end">{{ number_format($donHang->tong_tien) }}đ</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">Phí ship:</td>
                                    <td class="text-end">{{ number_format($donHang->phi_van_chuyen) }}đ</td>
                                </tr>
                                @if($donHang->tien_giam > 0)
                                <tr>
                                    <td colspan="3" class="text-end text-success">Giảm giá:</td>
                                    <td class="text-end text-success">-{{ number_format($donHang->tien_giam) }}đ</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end fs-5 fw-bold">Tổng thanh toán:</td>
                                    <td class="fs-5 fw-bold text-end" style="color:var(--primary)">{{ number_format($donHang->thanh_tien) }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">📜 Lịch sử trạng thái</h6>
                    <div class="timeline">
                        @foreach($donHang->lichSus->sortByDesc('created_at') as $ls)
                        <div class="d-flex gap-3 mb-3 border-bottom pb-2">
                            <span class="badge bg-{{ ['cho_xac_nhan'=>'warning','da_xac_nhan'=>'info','dang_giao'=>'primary','da_hoan_thanh'=>'success','da_huy'=>'danger'][$ls->trang_thai] ?? 'secondary' }}" style="min-width: 100px;">
                                {{ ['cho_xac_nhan'=>'Chờ XN','da_xac_nhan'=>'Đã XN','dang_giao'=>'Đang giao','da_hoan_thanh'=>'Hoàn thành','da_huy'=>'Đã hủy'][$ls->trang_thai] ?? $ls->trang_thai }}
                            </span>
                            <span class="text-muted small">{{ $ls->created_at->format('d/m/Y H:i') }}</span>
                            @if($ls->nguoiThucHien)
                                <span class="text-dark small fw-medium"> - {{ $ls->nguoiThucHien->ho_ten }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">👤 Thông tin giao hàng</h6>
                    <p class="mb-2"><strong>Khách hàng:</strong> {{ $donHang->ho_ten_nhan }}</p>
                    <p class="mb-2"><strong>SĐT:</strong> {{ $donHang->so_dien_thoai_nhan }}</p>
                    <p class="mb-2"><strong>Địa chỉ:</strong> {{ $donHang->dia_chi_giao }}</p>
                    <p class="mb-2"><strong>Thanh toán:</strong> <span class="badge bg-light text-dark">{{ strtoupper($donHang->phuong_thuc_tt) }}</span></p>
                    <p class="mb-2"><strong>Ngày đặt:</strong> {{ $donHang->ngay_dat->format('d/m/Y H:i') }}</p>
                    @if($donHang->ngay_giao_du_kien)
                        <p class="mb-2"><strong>Giao dự kiến:</strong> {{ $donHang->ngay_giao_du_kien->format('d/m/Y') }}</p>
                    @endif
                    @if($donHang->khuyenMai)
                        <p class="mb-2"><strong>Mã KM:</strong> <span class="badge bg-success">{{ $donHang->khuyenMai->ma_km }}</span></p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">📋 Cập nhật trạng thái</h6>
                    <div class="mb-3">
                        <span class="badge bg-{{ $donHang->trangThaiLabel['class'] ?? 'secondary' }} fs-6 w-100 py-2">
                            {{ $donHang->trangThaiLabel['label'] ?? 'Không rõ' }}
                        </span>
                    </div>
                    
                    @if(!in_array($donHang->trang_thai, ['da_hoan_thanh','da_huy']))
                    <form action="{{ route('admin.don-hang.trang-thai', $donHang->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <select name="trang_thai" class="form-select">
                                @foreach(['da_xac_nhan'=>'Xác nhận đơn','dang_giao'=>'Đang giao hàng','da_hoan_thanh'=>'Hoàn thành','da_huy'=>'Hủy đơn'] as $v=>$l)
                                <option value="{{ $v }}" {{ $donHang->trang_thai===$v?'selected':'' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">Cập nhật</button>
                    </form>
                    @endif
                </div>
            </div>
            
            <a href="{{ route('admin.don-hang.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>
    </div> </div> @endsection