<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\SanPham;
class DanhMuc extends Model {
    protected $table = 'danh_mucs';
    protected $fillable = ['ten_danh_muc', 'trang_thai', 'so_lan_an_dm', 'ngay_xoa_dm'];
    public function sanPhams() { return $this->hasMany(SanPham::class, 'danh_muc_id'); }
    public function scopeActive($q) { return $q->where('trang_thai', true); }
}