{{-- resources/views/admin/khuyen-mai/index.blade.php --}}
@extends('layouts.admin')
@section('title','Khuyến mãi')
@section('page-title','Quản lý khuyến mãi')
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.khuyen-mai.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tạo mã mới</a>
</div>
<div class="stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Mã KM</th><th>Tên chương trình</th><th>Loại</th><th>Giảm</th><th>Đ.Tối thiểu</th><th>Thời gian</th><th>Đã dùng</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
            @foreach($khuyenMais as $km)
            <tr>
                <td><span class="badge bg-primary fs-6">{{ $km->ma_km }}</span></td>
                <td>{{ $km->ten_chuong_trinh }}</td>
                <td><span class="badge bg-{{ $km->loai==='mien_phi_ship'?'info':($km->loai==='phan_tram'?'warning text-dark':'success') }}">
                    {{ ['phan_tram'=>'Phần trăm','co_dinh'=>'Cố định','mien_phi_ship'=>'Free ship'][$km->loai] }}
                </span></td>
                <td>
                    @if($km->loai==='phan_tram') {{ $km->ty_le_giam }}%
                    @elseif($km->loai==='co_dinh') {{ number_format($km->so_tien_giam) }}đ
                    @else Miễn phí ship @endif
                </td>
                <td>{{ number_format($km->don_hang_toi_thieu) }}đ</td>
                <td class="small">{{ $km->ngay_bat_dau->format('d/m/Y') }} - {{ $km->ngay_ket_thuc->format('d/m/Y') }}</td>
                <td>{{ $km->so_lan_da_dung }}{{ $km->so_luong_ma ? '/'.$km->so_luong_ma : '' }}</td>
                <td>
                    @if($km->isConHieuLuc())<span class="badge bg-success">Đang hoạt động</span>
                    @elseif(now() > $km->ngay_ket_thuc)<span class="badge bg-secondary">Hết hạn</span>
                    @else<span class="badge bg-warning text-dark">Tạm dừng</span>@endif
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.khuyen-mai.edit', $km->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.khuyen-mai.destroy', $km->id) }}" method="POST" onsubmit="return confirm('Xóa mã này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $khuyenMais->links() }}
</div>
@endsection