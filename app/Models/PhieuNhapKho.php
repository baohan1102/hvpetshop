<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PhieuNhapKho extends Model {
    protected $table = 'phieu_nhap_khos';
    protected $fillable = ['ma_nk', 'san_pham_id', 'nha_cung_cap_id', 'nguoi_tao_id', 'so_luong', 'gia_nhap', 'tong_tien', 'ghi_chu'];
    public function sanPham() { return $this->belongsTo(SanPham::class, 'san_pham_id'); }
    public function nhaCungCap() { return $this->belongsTo(NhaCungCap::class, 'nha_cung_cap_id'); }
    public function nguoiTao() { return $this->belongsTo(User::class, 'nguoi_tao_id'); }
}