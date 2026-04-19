<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LichSuDonHang extends Model {
    protected $table = 'lich_su_don_hangs';
    protected $fillable = ['don_hang_id', 'trang_thai', 'danh_gia', 'nhan_xet', 'ly_do_huy', 'thuc_hien_boi'];
    public function donHang() { return $this->belongsTo(DonHang::class, 'don_hang_id'); }
    public function nguoiThucHien() { return $this->belongsTo(User::class, 'thuc_hien_boi'); }
}