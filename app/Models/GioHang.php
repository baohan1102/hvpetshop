<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GioHang extends Model {
    protected $table = 'gio_hangs';
    protected $fillable = ['user_id', 'san_pham_id', 'so_luong', 'gia'];
    public function user() { return $this->belongsTo(User::class); }
    public function sanPham() { return $this->belongsTo(SanPham::class, 'san_pham_id'); }
    public function thanhTien() { return $this->so_luong * $this->gia; }
}