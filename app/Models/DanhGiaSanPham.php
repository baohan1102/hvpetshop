<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DanhGiaSanPham extends Model {
    protected $table = 'danh_gia_san_phams';
    protected $fillable = ['user_id', 'san_pham_id', 'don_hang_id', 'so_sao', 'nhan_xet', 'hinh_anh', 'da_duyet'];
    public function user() { return $this->belongsTo(User::class); }
    public function sanPham() { return $this->belongsTo(SanPham::class, 'san_pham_id'); }
    public function donHang() { return $this->belongsTo(DonHang::class, 'don_hang_id'); }
    public function getHinhAnhUrlAttribute() {
        if ($this->hinh_anh) return asset('storage/' . $this->hinh_anh);
        return null;
    }
}