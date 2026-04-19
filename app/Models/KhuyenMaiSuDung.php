<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KhuyenMaiSuDung extends Model {
    protected $table = 'khuyen_mai_su_dungs';
    protected $fillable = ['user_id', 'khuyen_mai_id', 'don_hang_id'];
    public function user() { return $this->belongsTo(User::class); }
    public function khuyenMai() { return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id'); }
    public function donHang() { return $this->belongsTo(DonHang::class, 'don_hang_id'); }
}