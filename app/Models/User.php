<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'ho_ten', 'so_dien_thoai', 'mat_khau', 'email',
        'ngay_sinh', 'dia_chi', 'vai_tro', 'trang_thai',
        'mat_khau_mac_dinh', 'reset_token', 'reset_token_expires_at',
    ];

    protected $hidden = ['mat_khau', 'remember_token'];

    protected $casts = ['ngay_sinh' => 'date', 'trang_thai' => 'boolean'];

    public function getAuthPassword() { return $this->mat_khau; }

    public function isChuCuaHang() { return $this->vai_tro === 'chu_cua_hang'; }
    public function isNhanVien() { return $this->vai_tro === 'nhan_vien'; }
    public function isKhachHang() { return $this->vai_tro === 'khach_hang'; }
    public function isAdmin() { return in_array($this->vai_tro, ['chu_cua_hang', 'nhan_vien']); }

    public function donHangs() { return $this->hasMany(DonHang::class); }
    public function gioHangs() { return $this->hasMany(GioHang::class); }
    public function diaChis() { return $this->hasMany(DiaChi::class); }
    public function danhGias() { return $this->hasMany(DanhGiaSanPham::class); }

    public function diaChiMacDinh()
    {
        return $this->diaChis()->where('la_mac_dinh', true)->first();
    }

    public function tongChiTieu()
    {
        return $this->donHangs()->where('trang_thai', 'da_hoan_thanh')->sum('thanh_tien');
    }

    public function loaiKhachHang()
    {
        $tong = $this->tongChiTieu();
        if ($tong >= 5000000) return ['loai' => 'vang', 'ten' => 'Vàng', 'color' => 'gold'];
        if ($tong >= 2000000) return ['loai' => 'bac', 'ten' => 'Bạc', 'color' => 'silver'];
        return ['loai' => 'dong', 'ten' => 'Đồng', 'color' => '#cd7f32'];
    }
}