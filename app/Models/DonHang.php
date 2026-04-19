<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model {
    protected $table = 'don_hangs';
    protected $fillable = [
        'ma_dh', 'user_id', 'nhan_vien_id', 'khuyen_mai_id',
        'tong_tien', 'phi_van_chuyen', 'tien_giam', 'thanh_tien',
        'dia_chi_giao', 'so_dien_thoai_nhan', 'ho_ten_nhan',
        'phuong_thuc_tt', 'trang_thai', 'ngay_dat', 'ngay_giao_du_kien',
        'ngay_giao_thuc_te', 'ly_do_huy', 'da_nhan_hang'
    ];
    protected $casts = [
        'ngay_dat' => 'datetime',
        'ngay_giao_du_kien' => 'datetime',
        'ngay_giao_thuc_te' => 'datetime',
        'da_nhan_hang' => 'boolean',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function nhanVien() { return $this->belongsTo(User::class, 'nhan_vien_id'); }
    public function khuyenMai() { return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id'); }
    public function chiTiets() { return $this->hasMany(ChiTietDonHang::class, 'don_hang_id'); }
    public function lichSus() { return $this->hasMany(LichSuDonHang::class, 'don_hang_id'); }
    public function danhGias() { return $this->hasMany(DanhGiaSanPham::class, 'don_hang_id'); }

    public function getTrangThaiLabelAttribute()
    {
        return [
            'cho_xac_nhan' => ['label' => 'Chờ xác nhận', 'class' => 'warning'],
            'da_xac_nhan'  => ['label' => 'Đã xác nhận', 'class' => 'info'],
            'dang_giao'    => ['label' => 'Đang giao', 'class' => 'primary'],
            'da_hoan_thanh'=> ['label' => 'Đã hoàn thành', 'class' => 'success'],
            'da_huy'       => ['label' => 'Đã hủy', 'class' => 'danger'],
        ][$this->trang_thai] ?? ['label' => $this->trang_thai, 'class' => 'secondary'];
    }

    public function canHuy() { return $this->trang_thai === 'cho_xac_nhan'; }
    public function canNhanHang() { return $this->trang_thai === 'dang_giao' && !$this->da_nhan_hang; }
    public function canDanhGia()
    {
        return $this->trang_thai === 'da_hoan_thanh' || $this->da_nhan_hang;
    }
}