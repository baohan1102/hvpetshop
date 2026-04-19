@extends('layouts.admin')
@section('title', 'Quản lý sản phẩm')
@section('page-title', 'Quản lý sản phẩm')

@section('content')

<div class="d-flex justify-content-between align-items-start mb-4 w-100">    <form class="d-flex gap-2 flex-wrap" method="GET">
        <input type="text" name="tu_khoa" class="form-control" style="max-width:200px" placeholder="Tìm sản phẩm..." value="{{ request('tu_khoa') }}">

```
    <select name="danh_muc_id" class="form-select" style="max-width:160px">
        <option value="">Tất cả danh mục</option>
        @foreach($danhMucs as $dm)
            <option value="{{ $dm->id }}" {{ request('danh_muc_id')==$dm->id?'selected':'' }}>
                {{ $dm->ten_danh_muc }}
            </option>
        @endforeach
    </select>

    <select name="trang_thai" class="form-select" style="max-width:130px">
        <option value="">Tất cả</option>
        <option value="1" {{ request('trang_thai')==='1'?'selected':'' }}>Hiển thị</option>
        <option value="0" {{ request('trang_thai')==='0'?'selected':'' }}>Đã ẩn</option>
    </select>

    <button class="btn btn-outline-secondary">Lọc</button>
</form>

<a href="{{ route('admin.san-pham.create') }}" 
   class="btn btn-primary fw-bold ms-auto">
    <i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm
</a>
```

</div> {{-- ✅ FIX: đóng div ở đây --}}

<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Đánh giá</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sanPhams as $sp)
                <tr class="{{ !$sp->trang_thai ? 'table-secondary' : '' }}">
                    <td>
                        <img src="{{ $sp->hinh_anh_url }}" alt="" 
                             style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                    </td>

```
                <td>
                    <div class="fw-semibold">
                        {{ Str::limit($sp->ten_sp, 40) }}
                    </div>
                    <small class="text-muted">{{ $sp->ma_sp }}</small>
                </td>

                <td>{{ $sp->danhMuc->ten_danh_muc ?? '-' }}</td>

                <td class="fw-semibold" style="color:var(--primary)">
                    {{ number_format($sp->gia) }}đ
                </td>

                <td>
                    <span class="badge 
                        {{ $sp->so_luong == 0 ? 'bg-danger' 
                        : ($sp->so_luong <= $sp->nguong_canh_bao ? 'bg-warning text-dark' : 'bg-success') }}">
                        {{ $sp->so_luong }}
                    </span>
                </td>

                <td>
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-star-fill text-warning" style="font-size:12px"></i>
                        {{ $sp->danhGiaTrungBinh() }} ({{ $sp->soLuongDanhGia() }})
                    </div>
                </td>

                <td>
                    <span class="badge {{ $sp->trang_thai ? 'bg-success' : 'bg-secondary' }}">
                        {{ $sp->trang_thai ? 'Hiển thị' : 'Đã ẩn' }}
                    </span>
                </td>

                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.san-pham.edit', $sp->id) }}" 
                           class="btn btn-sm btn-outline-primary" title="Sửa">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('admin.san-pham.toggle', $sp->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm {{ $sp->trang_thai ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                    title="{{ $sp->trang_thai ? 'Ẩn' : 'Hiện' }}">
                                <i class="bi {{ $sp->trang_thai ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                            </button>
                        </form>

                        <a href="{{ route('admin.san-pham.show', $sp->id) }}" 
                           class="btn btn-sm btn-outline-info" title="Xem">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $sanPhams->links() }}
```

</div>
@endsection
