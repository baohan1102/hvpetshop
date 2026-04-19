@extends('layouts.app')
@section('title', 'Giỏ hàng - HV Pet Shop')
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-1">Giỏ hàng của bạn</h2>
    <p class="text-muted mb-4">Bạn đang có {{ $gioHangs->count() }} sản phẩm trong giỏ hàng</p>

    @if($gioHangs->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-cart-x display-1 text-muted"></i>
        <h4 class="mt-3 text-muted">Giỏ hàng trống</h4>
        <a href="{{ route('san-pham.danh-sach') }}" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
    </div>
    @else
    <div class="row g-4">
        {{-- GIỎ HÀNG --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">
                                    <input type="checkbox" id="checkAll" class="form-check-input me-2" checked>
                                    SẢN PHẨM
                                </th>
                                <th>GIÁ</th>
                                <th class="text-center">SỐ LƯỢNG</th>
                                <th class="text-end">TỔNG CỘNG</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gioHangs as $item)
                            <tr data-id="{{ $item->id }}" data-price="{{ $item->gia }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="checkbox" class="form-check-input item-check" data-id="{{ $item->id }}" checked>
                                        <img src="{{ $item->sanPham->hinh_anh_url }}" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                                        <div>
                                            <a href="{{ route('san-pham.chi-tiet', $item->san_pham_id) }}" class="fw-semibold text-dark text-decoration-none">
                                                {{ $item->sanPham->ten_sp }}
                                            </a>
                                            <div class="text-muted small">{{ $item->sanPham->danhMuc->ten_danh_muc ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($item->gia) }}đ</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center border rounded-pill overflow-hidden" style="width:110px;margin:auto">
                                        <button class="btn btn-light px-2 py-1 btn-decrease" data-id="{{ $item->id }}">−</button>
                                        <input type="number" class="form-control border-0 text-center qty-input" value="{{ $item->so_luong }}" min="1" max="{{ $item->sanPham->so_luong }}" data-id="{{ $item->id }}" style="width:45px">
                                        <button class="btn btn-light px-2 py-1 btn-increase" data-id="{{ $item->id }}" data-max="{{ $item->sanPham->so_luong }}">+</button>
                                    </div>
                                </td>
                                <td class="text-end fw-bold item-subtotal" style="color:var(--primary)" data-id="{{ $item->id }}">
                                    {{ number_format($item->so_luong * $item->gia) }}đ
                                </td>
                                <td>
                                    <button class="btn btn-sm text-danger btn-remove" data-id="{{ $item->id }}" title="Xóa">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <a href="{{ route('san-pham.danh-sach') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Tiếp tục mua sắm
                </a>
                <form action="{{ route('gio-hang.xoa-tat-ca') }}" method="POST" onsubmit="return confirm('Xóa toàn bộ giỏ hàng?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger">Xóa toàn bộ giỏ hàng</button>
                </form>
            </div>
        </div>

        {{-- TÓM TẮT ĐƠN HÀNG --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top:80px">
                <h5 class="fw-bold mb-4">Tóm tắt đơn hàng</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tạm tính</span>
                    <span id="subtotalDisplay">{{ number_format($tongTien) }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Phí vận chuyển</span>
                    <span id="shipDisplay">{{ number_format($phiShip) }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Giảm giá</span>
                    <span class="text-success" id="discountDisplay">-0đ</span>
                </div>

                {{-- MÃ GIẢM GIÁ với DROPDOWN --}}
                <div class="mb-3">
                    <label class="fw-semibold small text-uppercase mb-2">Mã giảm giá</label>
                    {{-- Dropdown hiện tất cả mã --}}
                    <div class="dropdown mb-2">
                        <button class="btn btn-outline-secondary btn-sm w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-tag me-1"></i>Xem các mã có sẵn
                        </button>
                        <ul class="dropdown-menu w-100">
                            @foreach($khuyenMais as $km)
                            <li>
                                <button class="dropdown-item py-2 km-select-btn" data-code="{{ $km->ma_km }}" type="button">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold text-primary">{{ $km->ma_km }}</span>
                                        @if($km->loai === 'phan_tram')
                                        <span class="badge bg-success">-{{ $km->ty_le_giam }}%</span>
                                        @elseif($km->loai === 'co_dinh')
                                        <span class="badge bg-warning text-dark">-{{ number_format($km->so_tien_giam) }}đ</span>
                                        @else
                                        <span class="badge bg-info">Miễn ship</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted">{{ $km->ten_chuong_trinh }}</div>
                                    <div class="small text-muted">Đơn tối thiểu: {{ number_format($km->don_hang_toi_thieu) }}đ</div>
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="input-group">
                        <input type="text" id="maKm" class="form-control text-uppercase" placeholder="Nhập mã...">
                        <button class="btn btn-primary" id="btnApKm" type="button">Áp dụng</button>
                    </div>
                    <div id="kmMsg" class="mt-1 small"></div>
                </div>

                <input type="hidden" id="kmId" value="">

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-5">Tổng cộng</span>
                    <span class="fw-bold fs-4" style="color:var(--primary)" id="totalDisplay">{{ number_format($tongTien + $phiShip) }}đ</span>
                </div>
                <small class="text-muted d-block text-center mb-3">(Đã bao gồm VAT nếu có)</small>

                <button class="btn btn-primary w-100 btn-lg fw-bold" id="btnCheckout">
                    Tiến hành thanh toán <i class="bi bi-arrow-right ms-2"></i>
                </button>

                <div class="text-center mt-3">
                    <small class="text-muted">Phương thức thanh toán hỗ trợ</small><br>
                    <span class="badge bg-light text-dark border me-1">VISA</span>
                    <span class="badge bg-light text-dark border me-1">MOMO</span>
                    <span class="badge bg-light text-dark border">COD</span>
                </div>

                <div class="alert alert-light border mt-3 py-2 mb-0">
                    <i class="bi bi-shield-check text-success me-2"></i>
                    <strong>Mua sắm an tâm</strong><br>
                    <small>Chính sách đổi trả trong vòng 7 ngày nếu có lỗi từ nhà sản xuất.</small>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;
let currentSubtotal = {{ $tongTien }};
let currentShip = {{ $phiShip }};
let currentDiscount = 0;
let kmId = null;

function updateTotal() {
    document.getElementById('subtotalDisplay').textContent = formatMoney(currentSubtotal) + 'đ';
    document.getElementById('shipDisplay').textContent = currentShip === 0 ? 'Miễn phí' : formatMoney(currentShip) + 'đ';
    document.getElementById('discountDisplay').textContent = '-' + formatMoney(currentDiscount) + 'đ';
    document.getElementById('totalDisplay').textContent = formatMoney(currentSubtotal + currentShip - currentDiscount) + 'đ';
}

function formatMoney(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

// Checkbox chọn sản phẩm
document.getElementById('checkAll')?.addEventListener('change', function() {
    document.querySelectorAll('.item-check').forEach(cb => cb.checked = this.checked);
    recalcSubtotal();
});

document.querySelectorAll('.item-check').forEach(cb => cb.addEventListener('change', recalcSubtotal));

function recalcSubtotal() {
    let total = 0;
    document.querySelectorAll('.item-check:checked').forEach(cb => {
        const row = cb.closest('tr');
        const price = parseFloat(row.dataset.price);
        const qty = parseInt(row.querySelector('.qty-input').value);
        total += price * qty;
    });
    currentSubtotal = total;
    currentDiscount = 0; kmId = null;
    document.getElementById('kmMsg').textContent = '';
    document.getElementById('maKm').value = '';
    currentShip = {{ $phiShip }};
    updateTotal();
}

// Thay đổi số lượng
document.querySelectorAll('.btn-decrease').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const input = document.querySelector(`.qty-input[data-id="${id}"]`);
        if (parseInt(input.value) > 1) { input.value = parseInt(input.value) - 1; updateQty(id, input.value); }
    });
});
document.querySelectorAll('.btn-increase').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id, max = this.dataset.max;
        const input = document.querySelector(`.qty-input[data-id="${id}"]`);
        if (parseInt(input.value) < parseInt(max)) { input.value = parseInt(input.value) + 1; updateQty(id, input.value); }
    });
});
document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('change', function() { updateQty(this.dataset.id, this.value); });
});

function updateQty(id, qty) {
    fetch(`/gio-hang/cap-nhat/${id}`, {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ so_luong: qty })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            document.querySelector(`.item-subtotal[data-id="${id}"]`).textContent = data.thanh_tien_format;
            recalcSubtotal();
        }
    });
}

// Xóa sản phẩm
document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', function() {
        if(!confirm('Xóa sản phẩm này?')) return;
        const id = this.dataset.id;
        fetch(`/gio-hang/xoa/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF }
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) { this.closest('tr').remove(); updateCartCount(data.count); recalcSubtotal(); }
        });
    });
});

// Chọn mã từ dropdown
document.querySelectorAll('.km-select-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('maKm').value = this.dataset.code;
    });
});

// Áp dụng mã giảm giá
document.getElementById('btnApKm')?.addEventListener('click', function() {
    const ma = document.getElementById('maKm').value;
    if(!ma) return;
    fetch('{{ route("gio-hang.ap-dung-km") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ ma_km: ma, tong_tien: currentSubtotal })
    })
    .then(r => r.json())
    .then(data => {
        const msg = document.getElementById('kmMsg');
        if(data.success) {
            msg.className = 'mt-1 small text-success';
            msg.textContent = '✓ ' + data.message;
            currentDiscount = data.giam;
            currentShip = data.phi_ship_moi;
            kmId = data.khuyen_mai_id;
            document.getElementById('kmId').value = kmId;
            updateTotal();
        } else {
            msg.className = 'mt-1 small text-danger';
            msg.textContent = '✗ ' + data.message;
            currentDiscount = 0; kmId = null;
            updateTotal();
        }
    });
});

// Tiến hành thanh toán
document.getElementById('btnCheckout')?.addEventListener('click', function() {
    const selected = Array.from(document.querySelectorAll('.item-check:checked')).map(cb => cb.dataset.id);
    if(selected.length === 0) { alert('Vui lòng chọn ít nhất 1 sản phẩm!'); return; }

    fetch('{{ route("gio-hang.prepare-checkout") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ selected_ids: selected, khuyen_mai_id: kmId })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route("don-hang.checkout") }}';
            if(kmId) { const inp = document.createElement('input'); inp.name='khuyen_mai_id'; inp.value=kmId; form.appendChild(inp); }
            document.body.appendChild(form);
            form.submit();
        }
    });
});

updateTotal();
</script>
@endpush
@endsection