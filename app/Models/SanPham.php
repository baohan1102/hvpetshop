<?php
// app/Models/SanPham.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\DanhMuc;
use App\Models\NhaCungCap;
use App\Models\DanhGiaSanPham;
use App\Models\ChiTietDonHang;
class SanPham extends Model
{
    protected $table = 'san_phams';
    protected $fillable = [
        'ma_sp', 'danh_muc_id', 'nha_cung_cap_id', 'ten_sp', 'mo_ta',
        'hinh_anh', 'gia', 'so_luong', 'so_luong_kho', 'nguong_canh_bao',
        'trang_thai', 'la_moi'
    ];
    protected $casts = ['trang_thai' => 'boolean', 'la_moi' => 'boolean'];

    public function danhMuc() { return $this->belongsTo(DanhMuc::class, 'danh_muc_id'); }
    public function nhaCungCap() { return $this->belongsTo(NhaCungCap::class, 'nha_cung_cap_id'); }
    public function danhGias() { return $this->hasMany(DanhGiaSanPham::class, 'san_pham_id'); }
    public function chiTietDonHangs() { return $this->hasMany(ChiTietDonHang::class, 'san_pham_id'); }

    public function danhGiaTrungBinh()
    {
        $avg = $this->danhGias()->where('da_duyet', true)->avg('so_sao');
        return round($avg ?? 0, 1);
    }

    public function soLuongDanhGia()
    {
        return $this->danhGias()->where('da_duyet', true)->count();
    }

public function getHinhAnhUrlAttribute()
{
    if ($this->hinh_anh) {
        if (str_starts_with($this->hinh_anh, 'http')) {
            return $this->hinh_anh;
        }
        return asset('storage/' . $this->hinh_anh);
    }
    return 'https://via.placeholder.com/400x300/00BCD4/ffffff?text=HV+Pet';
}

    public function scopeActive($q) { return $q->where('trang_thai', true); }
    public function scopeConHang($q) { return $q->where('so_luong', '>', 0); }
}