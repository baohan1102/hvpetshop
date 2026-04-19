{{-- resources/views/admin/kho/index.blade.php --}}
@extends('layouts.admin')
@section('title','Kho hàng')
@section('page-title','Quản lý kho hàng')
@section('content')
<div class="row g-4">
    {{-- Form nhập kho --}}
    <div class="col-md-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">📥 Nhập kho</h6>
            <form action="{{ route('admin.kho.nhap') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sản phẩm *</label>
                    <select name="san_pham_id" class="form-select" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        @foreach($sanPhamList as $sp)<option value="{{ $sp->id }}">{{ $sp->ten_sp }} (Tồn: {{ $sp->so_luong }})</option>@endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nhà cung cấp</label>
                    <select name="nha_cung_cap_id" class="form-select">
                        <option value="">-- Không --</option>
                        @foreach($nhaCungCaps as $ncc)<option value="{{ $ncc->id }}">{{ $ncc->ten_ncc }}</option>@endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Số lượng nhập *</label>
                    <input type="number" name="so_luong" class="form-control" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Giá nhập (đ) *</label>
                    <input type="number" name="gia_nhap" class="form-control" min="0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
                </div>
                <button class="btn btn-success w-100 fw-bold">Nhập kho</button>
            </form>
        </div>
    </div>

    {{-- Danh sách tồn kho --}}
    <div class="col-md-8">
        <div class="stat-card mb-4">
            <h6 class="fw-bold mb-3">📊 Tồn kho hiện tại</h6>
            <form class="d-flex gap-2 mb-3" method="GET">
                <input type="text" name="tu_khoa" class="form-control form-control-sm" placeholder="Tìm sản phẩm..." style="max-width:200px" value="{{ request('tu_khoa') }}">
                <button class="btn btn-sm btn-outline-secondary">Tìm</button>
                <a href="{{ route('admin.kho.thong-ke') }}" class="btn btn-sm btn-outline-warning ms-auto">📊 Thống kê</a>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>Sản phẩm</th><th>Danh mục</th><th>Tồn kho</th><th>Ngưỡng CB</th><th>Tình trạng</th></tr></thead>
                    <tbody>
                    @foreach($sanPhams as $sp)
                    <tr>
                        <td>
                            <div class="fw-semibold small">{{ $sp->ten_sp }}</div>
                            <div class="text-muted" style="font-size:11px">{{ $sp->ma_sp }}</div>
                        </td>
                        <td class="small">{{ $sp->danhMuc->ten_danh_muc ?? '-' }}</td>
                        <td><strong class="{{ $sp->so_luong == 0 ? 'text-danger' : ($sp->so_luong <= $sp->nguong_canh_bao ? 'text-warning' : 'text-success') }}">{{ $sp->so_luong }}</strong></td>
                        <td>{{ $sp->nguong_canh_bao }}</td>
                        <td>
                            @if($sp->so_luong == 0) <span class="badge bg-danger">Hết hàng</span>
                            @elseif($sp->so_luong <= $sp->nguong_canh_bao) <span class="badge bg-warning text-dark">Sắp hết</span>
                            @else <span class="badge bg-success">Đủ hàng</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $sanPhams->links() }}
        </div>

        {{-- Phiếu nhập gần đây --}}
        <div class="stat-card">
            <h6 class="fw-bold mb-3">📋 Phiếu nhập gần đây</h6>
            <div class="table-responsive">
                <table class="table align-middle small">
                    <thead><tr><th>Mã NK</th><th>Sản phẩm</th><th>Nhà CC</th><th>SL</th><th>Giá nhập</th><th>Tổng</th><th>Ngày</th></tr></thead>
                    <tbody>
                    @foreach($phieuNhaps as $pn)
                    <tr>
                        <td class="fw-semibold">{{ $pn->ma_nk }}</td>
                        <td>{{ Str::limit($pn->sanPham->ten_sp, 25) }}</td>
                        <td>{{ $pn->nhaCungCap?->ten_ncc ?? '-' }}</td>
                        <td>{{ $pn->so_luong }}</td>
                        <td>{{ number_format($pn->gia_nhap) }}đ</td>
                        <td class="fw-bold">{{ number_format($pn->tong_tien) }}đ</td>
                        <td class="text-muted">{{ $pn->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection