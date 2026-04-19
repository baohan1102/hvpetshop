<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KhuyenMai extends Model {
    protected $table = 'khuyen_mais';
    protected $fillable = [
        'ma_km', 'ten_chuong_trinh', 'ty_le_giam', 'so_tien_giam', 'loai',
        'don_hang_toi_thieu', 'giam_toi_da', 'mien_phi_ship_tu',
        'so_luong_ma', 'so_lan_da_dung', 'gioi_han_moi_kh',
        'ngay_bat_dau', 'ngay_ket_thuc', 'trang_thai'
    ];
    protected $casts = ['ngay_bat_dau' => 'date', 'ngay_ket_thuc' => 'date', 'trang_thai' => 'boolean'];

    public function donHangs() { return $this->hasMany(DonHang::class, 'khuyen_mai_id'); }
    public function suDungs() { return $this->hasMany(KhuyenMaiSuDung::class, 'khuyen_mai_id'); }

    public function isConHieuLuc()
    {
        $now = Carbon::now();
        return $this->trang_thai
            && $now->between($this->ngay_bat_dau, $this->ngay_ket_thuc)
            && ($this->so_luong_ma === null || $this->so_lan_da_dung < $this->so_luong_ma);
    }

    public function tinhGiam($tongTien, $phiShip = 35000)
    {
        if ($tongTien < $this->don_hang_toi_thieu) return ['giam' => 0, 'phi_ship_moi' => $phiShip];

        if ($this->loai === 'mien_phi_ship') {
            return ['giam' => $phiShip, 'phi_ship_moi' => 0];
        }
        if ($this->loai === 'phan_tram') {
            $giam = $tongTien * ($this->ty_le_giam / 100);
            if ($this->giam_toi_da) $giam = min($giam, $this->giam_toi_da);
            return ['giam' => round($giam), 'phi_ship_moi' => $phiShip];
        }
        if ($this->loai === 'co_dinh') {
            return ['giam' => min($this->so_tien_giam, $tongTien), 'phi_ship_moi' => $phiShip];
        }
        return ['giam' => 0, 'phi_ship_moi' => $phiShip];
    }
}