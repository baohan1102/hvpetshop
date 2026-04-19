<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model {
    protected $table = 'nha_cung_caps';
    protected $fillable = ['ten_ncc', 'so_dien_thoai', 'email', 'dia_chi', 'ghi_chu', 'trang_thai'];
    public function sanPhams() { return $this->hasMany(SanPham::class, 'nha_cung_cap_id'); }
    public function phieuNhapKhos() { return $this->hasMany(PhieuNhapKho::class, 'nha_cung_cap_id'); }
}