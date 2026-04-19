@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng - HV Pet Shop')
@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('don-hang.lich-su') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <h2 class="fw-bold mb-0">Chi tiết đơn hàng</h2>
        <span class="badge bg-{{ $donHang->trangThaiLabel['class'] }} fs-6">{{ $donHang->trangThaiLabel['label'] }}</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- THÔNG TIN ĐƠN HÀNG --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3">📋 Thông tin đơn hàng</h5>
                <div class="row g-3">
                    <div class="col-sm-6"><small class="text-muted">Mã đơn</small><div class="fw-bold">{{ $donHang->ma_dh }}</div></div>
                    <div class="col-sm-6"><small class="text-muted">Ngày đặt</small><div class="fw-semibold">{{ $donHang->ngay_dat->format('d/m/Y H:i') }}</div></div>
                    <div class="col-sm-6"><small class="text-muted">Giao hàng dự kiến</small><div class="fw-semibold">{{ $donHang->ngay_giao_du_kien?->format('d/m/Y') ?? 'Đang cập nhật' }}</div></div>
                    @if($donHang->ngay_giao_thuc_te)
                    <div class="col-sm-6"><small class="text-muted">Ngày giao thực tế</small><div class="fw-semibold text-success">{{ $donHang->ngay_giao_thuc_te->format('d/m/Y') }}</div></div>
                    @endif
                    <div class="col-sm-6"><small class="text-muted">Phương thức thanh toán</small><div class="fw-semibold">{{ strtoupper($donHang->phuong_thuc_tt) }}</div></div>
                    <div class="col-sm-6"><small class="text-muted">Địa chỉ giao hàng</small>
                        <div class="fw-semibold">{{ $donHang->ho_ten_nhan }}</div>
                        <div class="small text-muted">{{ $donHang->so_dien_thoai_nhan }}</div>
                        <div class="small text-muted">{{ $donHang->dia_chi_giao }}</div>
                    </div>
                </div>
            </div>

            {{-- SẢN PHẨM --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3">🛍 Sản phẩm đã đặt</h5>
                @foreach($donHang->chiTiets as $ct)
                <div class="d-flex gap-3 align-items-center mb-3 pb-3 border-bottom">
                    <img src="{{ $ct->sanPham->hinh_anh_url }}" alt="" style="width:70px;height:70px;object-fit:cover;border-radius:10px">
                    <div class="flex-grow-1">
                        <a href="{{ route('san-pham.chi-tiet', $ct->san_pham_id) }}" class="fw-semibold text-dark text-decoration-none">{{ $ct->sanPham->ten_sp }}</a>
                        <div class="text-muted small">Đơn giá: {{ number_format($ct->gia) }}đ × {{ $ct->so_luong }}</div>
                    </div>
                    <div class="fw-bold" style="color:var(--primary)">{{ number_format($ct->thanh_tien) }}đ</div>
                </div>
                @endforeach

                <div class="mt-2">
                    <div class="d-flex justify-content-between text-muted small mb-1"><span>Tạm tính</span><span>{{ number_format($donHang->tong_tien) }}đ</span></div>
                    <div class="d-flex justify-content-between text-muted small mb-1"><span>Phí vận chuyển</span><span>{{ $donHang->phi_van_chuyen > 0 ? number_format($donHang->phi_van_chuyen).'đ' : 'Miễn phí' }}</span></div>
                    @if($donHang->tien_giam > 0)
                    <div class="d-flex justify-content-between text-success small mb-1"><span>Giảm giá ({{ $donHang->khuyenMai?->ma_km }})</span><span>-{{ number_format($donHang->tien_giam) }}đ</span></div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-2 pt-2 border-top">
                        <span>Tổng thanh toán</span><span style="color:var(--primary)">{{ number_format($donHang->thanh_tien) }}đ</span>
                    </div>
                </div>
            </div>

            {{-- BUTTON NHẬN HÀNG --}}
            @if($donHang->canNhanHang())
            <div class="alert alert-warning d-flex align-items-center justify-content-between p-4 rounded-4 mb-4">
                <div><i class="bi bi-truck fs-3 me-3"></i><strong>Đơn hàng đang được giao đến bạn!</strong><br>
                <small>Khi nhận được hàng, vui lòng bấm xác nhận để hoàn tất đơn hàng.</small></div>
                <form action="{{ route('don-hang.nhan-hang', $donHang->id) }}" method="POST" onsubmit="return confirm('Xác nhận đã nhận được hàng?')">
                    @csrf
                    <button class="btn btn-success btn-lg fw-bold ms-3"><i class="bi bi-check-lg me-2"></i>Đã nhận được hàng</button>
                </form>
            </div>
            @endif

            {{-- FORM ĐÁNH GIÁ --}}
            @if($donHang->canDanhGia())
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-4">⭐ Đánh giá sản phẩm</h5>
                @foreach($donHang->chiTiets as $ct)
                @php
                    $daDanhGia = $donHang->danhGias->where('san_pham_id', $ct->san_pham_id)->first();
                @endphp
                <div class="border rounded-3 p-3 mb-3">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <img src="{{ $ct->sanPham->hinh_anh_url }}" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                        <div class="fw-semibold">{{ $ct->sanPham->ten_sp }}</div>
                    </div>
                    @if($daDanhGia)
                    <div class="alert alert-success py-2 mb-0">
                        <i class="bi bi-check-circle me-2"></i>Bạn đã đánh giá sản phẩm này
                        <div>@for($i=1;$i<=5;$i++)<i class="bi bi-star-fill star-filled {{ $i > $daDanhGia->so_sao ? 'opacity-25':'' }}"></i>@endfor</div>
                        @if($daDanhGia->nhan_xet)<p class="mb-0 mt-1 small">{{ $daDanhGia->nhan_xet }}</p>@endif
                    </div>
                    @else
                    <form action="{{ route('don-hang.danh-gia', $donHang->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="san_pham_id" value="{{ $ct->san_pham_id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Đánh giá sao *</label>
                            <div class="star-rating d-flex gap-2 fs-3" data-field="so_sao_{{ $ct->san_pham_id }}">
                                @for($i=1;$i<=5;$i++)
                                <i class="bi bi-star star-btn text-warning" data-val="{{ $i }}" style="cursor:pointer"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="so_sao" class="so-sao-input" value="5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nhận xét</label>
                            <textarea name="nhan_xet" class="form-control" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Hình ảnh thực tế</label>
                            <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Gửi đánh giá</button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            {{-- LỊCH SỬ TRẠNG THÁI --}}
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">📜 Lịch sử trạng thái</h5>
                <div class="timeline">
                    @foreach($donHang->lichSus->sortByDesc('created_at') as $ls)
                    <div class="d-flex gap-3 mb-3">
                        <div class="text-center" style="min-width:30px">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width:30px;height:30px;background:var(--primary);font-size:12px">
                                <i class="bi bi-check-lg"></i>
                            </div>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ ['cho_xac_nhan'=>'Chờ xác nhận','da_xac_nhan'=>'Đã xác nhận','dang_giao'=>'Đang giao','da_hoan_thanh'=>'Đã hoàn thành','da_huy'=>'Đã hủy'][$ls->trang_thai] ?? $ls->trang_thai }}</div>
                            <div class="text-muted small">{{ $ls->created_at->format('d/m/Y H:i') }}</div>
                            @if($ls->ly_do_huy)<div class="text-danger small">Lý do: {{ $ls->ly_do_huy }}</div>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold mb-3">Hành động</h6>
                @if($donHang->canHuy())
                <button class="btn btn-outline-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#huyModal">
                    <i class="bi bi-x-circle me-2"></i>Hủy đơn hàng
                </button>
                @endif
                <a href="{{ route('don-hang.lich-su') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-2"></i>Về danh sách đơn
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal hủy --}}
@if($donHang->canHuy())
<div class="modal fade" id="huyModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content rounded-4">
        <div class="modal-header border-0"><h5 class="modal-title fw-bold">Hủy đơn hàng</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <form action="{{ route('don-hang.huy', $donHang->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <select name="ly_do" class="form-select" required>
                    <option value="">-- Chọn lý do --</option>
                    <option>Đặt nhầm sản phẩm</option>
                    <option>Đặt trùng đơn</option>
                    <option>Muốn thay đổi địa chỉ giao hàng</option>
                    <option>Tìm thấy giá rẻ hơn</option>
                    <option>Không có nhu cầu nữa</option>
                    <option>Lý do khác</option>
                </select>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
            </div>
        </form>
    </div></div>
</div>
@endif

@push('scripts')
<script>
// Star rating
document.querySelectorAll('.star-rating').forEach(container => {
    const stars = container.querySelectorAll('.star-btn');
    const input = container.closest('form').querySelector('.so-sao-input');
    stars.forEach((star, idx) => {
        star.addEventListener('mouseover', () => stars.forEach((s, i) => s.className = `bi ${i <= idx ? 'bi-star-fill' : 'bi-star'} text-warning`));
        star.addEventListener('click', () => { input.value = idx + 1; });
        container.addEventListener('mouseleave', () => {
            const val = parseInt(input.value);
            stars.forEach((s, i) => s.className = `bi ${i < val ? 'bi-star-fill' : 'bi-star'} text-warning`);
        });
    });
    // Set default
    stars.forEach((s, i) => s.className = `bi ${i < 5 ? 'bi-star-fill' : 'bi-star'} text-warning`);
});
</script>
@endpush
@endsection