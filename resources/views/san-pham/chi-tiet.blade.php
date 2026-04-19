@extends('layouts.app')
@section('title', $sanPham->ten_sp . ' - HV Pet Shop')
@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('san-pham.danh-sach', ['danh_muc' => $sanPham->danh_muc_id]) }}">{{ $sanPham->danhMuc->ten_danh_muc }}</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($sanPham->ten_sp, 40) }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- ẢNH SẢN PHẨM --}}
        <div class="col-md-5">
            <div class="border rounded-4 overflow-hidden mb-3">
                <img src="{{ $sanPham->hinh_anh_url }}" alt="{{ $sanPham->ten_sp }}" class="w-100" style="max-height:420px;object-fit:contain;padding:20px" id="mainImg">
            </div>
        </div>

        {{-- THÔNG TIN SẢN PHẨM --}}
        <div class="col-md-7">
            @if($sanPham->la_moi)<span class="badge mb-2" style="background:var(--primary)">BEST SELLER</span>@endif
            <h1 class="h3 fw-bold mb-2">{{ $sanPham->ten_sp }}</h1>
            <div class="text-muted small mb-3">
                Thương hiệu: <strong>HV PET</strong> | Mã sản phẩm: <strong>{{ $sanPham->ma_sp }}</strong>
            </div>

            {{-- Đánh giá --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                @php $avgRating = $sanPham->danhGiaTrungBinh(); @endphp
                @for($i = 1; $i <= 5; $i++)
                <i class="bi bi-star-fill star-filled {{ $i > $avgRating ? 'opacity-25' : '' }}"></i>
                @endfor
                <strong style="color:var(--primary)">{{ $avgRating }}</strong>
                <span class="text-muted">({{ $sanPham->soLuongDanhGia() }} đánh giá)</span>
            </div>

            {{-- Giá --}}
            <div class="mb-4">
                <span class="display-6 fw-bold" style="color:var(--primary)">{{ number_format($sanPham->gia) }}đ</span>
            </div>

            {{-- Mô tả ngắn --}}
            <p class="text-muted mb-4">{{ $sanPham->mo_ta }}</p>

            {{-- Badges --}}
            <div class="d-flex gap-2 flex-wrap mb-4">
                <span class="badge rounded-pill border" style="color:var(--primary);border-color:var(--primary)!important"><i class="bi bi-check-circle me-1"></i>100% Tự nhiên</span>
                <span class="badge rounded-pill border" style="color:var(--primary);border-color:var(--primary)!important"><i class="bi bi-check-circle me-1"></i>Giàu Protein</span>
                <span class="badge rounded-pill border" style="color:var(--primary);border-color:var(--primary)!important"><i class="bi bi-check-circle me-1"></i>Dễ tiêu hóa</span>
            </div>

            {{-- Tình trạng kho --}}
            @if($sanPham->so_luong > 0)
            <div class="alert alert-success py-2 mb-3"><i class="bi bi-check-circle me-2"></i>Còn hàng ({{ $sanPham->so_luong }} sản phẩm)</div>
            @else
            <div class="alert alert-danger py-2 mb-3"><i class="bi bi-x-circle me-2"></i>Hết hàng</div>
            @endif

            {{-- Form thêm giỏ hàng --}}
            @if($sanPham->so_luong > 0)
            <div class="d-flex align-items-center gap-3 mb-4">
                <label class="fw-semibold">SỐ LƯỢNG:</label>
                <div class="d-flex align-items-center border rounded-pill overflow-hidden">
                    <button class="btn btn-light px-3 py-2" onclick="changeQty(-1)">−</button>
                    <input type="number" id="qty" value="1" min="1" max="{{ $sanPham->so_luong }}" class="form-control border-0 text-center" style="width:60px">
                    <button class="btn btn-light px-3 py-2" onclick="changeQty(1)">+</button>
                </div>
            </div>

            {{-- 3 BUTTON SONG SONG: Chi tiết | Thêm giỏ hàng | Mua ngay --}}
            @auth
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <a href="{{ route('san-pham.chi-tiet', $sanPham->id) }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-info-circle me-1"></i>Chi tiết
                    </a>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-primary w-100" id="btnThemGio"
                        data-url="{{ route('gio-hang.them', $sanPham->id) }}">
                        <i class="bi bi-cart-plus me-1"></i>Thêm giỏ hàng
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-primary w-100" id="btnMuaNgay">
                        <i class="bi bi-lightning-fill me-1"></i>Mua ngay
                    </button>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập để mua hàng
            </a>
            @endauth
            @endif
        </div>
    </div>

    {{-- ĐÁNH GIÁ KHÁCH HÀNG --}}
    <div class="card border-0 shadow-sm rounded-4 p-4 mt-5">
        <h4 class="fw-bold mb-4">Đánh giá từ khách hàng</h4>
        <div class="row align-items-center g-4">
            <div class="col-md-2 text-center">
                <div class="display-3 fw-bold" style="color:var(--primary)">{{ $sanPham->danhGiaTrungBinh() }}</div>
                <div class="d-flex justify-content-center gap-1 mb-1">
                    @for($i=1;$i<=5;$i++)<i class="bi bi-star-fill star-filled {{ $i > $sanPham->danhGiaTrungBinh() ? 'opacity-25' : '' }}"></i>@endfor
                </div>
                <small class="text-muted">Dựa trên {{ $sanPham->soLuongDanhGia() }} nhận xét</small>
            </div>
            <div class="col-md-4">
                @for($star=5;$star>=1;$star--)
                @php $count = $thongKeDanhGia[$star] ?? 0; $pct = $sanPham->soLuongDanhGia() > 0 ? round($count/$sanPham->soLuongDanhGia()*100) : 0; @endphp
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="text-muted" style="width:10px">{{ $star }}</span>
                    <div class="progress flex-grow-1" style="height:8px">
                        <div class="progress-bar" style="width:{{ $pct }}%; background:var(--primary)"></div>
                    </div>
                    <span class="text-muted small" style="width:35px">{{ $pct }}%</span>
                </div>
                @endfor
            </div>
        </div>

        {{-- Danh sách đánh giá --}}
        <div class="mt-4">
            @forelse($danhGias as $dg)
            <div class="border-bottom py-3">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px">
                            {{ mb_substr($dg->user->ho_ten, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $dg->user->ho_ten }}</div>
                            <div class="d-flex gap-1">
                                @for($i=1;$i<=5;$i++)<i class="bi bi-star-fill star-filled {{ $i > $dg->so_sao ? 'opacity-25' : '' }}" style="font-size:12px"></i>@endfor
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">{{ $dg->created_at->diffForHumans() }}</small>
                </div>
                @if($dg->nhan_xet)<p class="mt-2 mb-1 text-muted">{{ $dg->nhan_xet }}</p>@endif
                @if($dg->hinh_anh)
                <img src="{{ $dg->hinh_anh_url }}" alt="review" class="rounded mt-2" style="max-height:120px">
                @endif
            </div>
            @empty
            <p class="text-muted text-center py-3">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
            @endforelse
        </div>
    </div>

    {{-- SẢN PHẨM LIÊN QUAN --}}
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="section-title fw-bold mb-0">Sản phẩm liên quan</h4>
            <a href="{{ route('san-pham.danh-sach', ['danh_muc' => $sanPham->danh_muc_id]) }}" class="text-primary">Xem tất cả <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            @foreach($sanPhamLienQuan as $sp)
            <div class="col-6 col-md-3">@include('components.product-card', ['sanPham' => $sp])</div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    let val = parseInt(input.value) + delta;
    val = Math.max(1, Math.min({{ $sanPham->so_luong }}, val));
    input.value = val;
}

document.getElementById('btnThemGio')?.addEventListener('click', function() {
    const qty = document.getElementById('qty').value;
    fetch(this.dataset.url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ so_luong: qty })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) { updateCartCount(data.count); showToast(data.message, 'success'); }
        else showToast(data.message, 'danger');
    });
});

document.getElementById('btnMuaNgay')?.addEventListener('click', function() {
    const qty = document.getElementById('qty').value;
    fetch('{{ route("gio-hang.them", $sanPham->id) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ so_luong: qty })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) window.location.href = '{{ route("gio-hang") }}';
        else showToast(data.message, 'danger');
    });
});
</script>
@endpush
@endsection