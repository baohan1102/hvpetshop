<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DiaChi extends Model {
    protected $table = 'dia_chis';
    protected $fillable = ['user_id', 'ho_ten', 'so_dien_thoai', 'dia_chi_chi_tiet', 'tinh_thanh', 'quan_huyen', 'la_mac_dinh'];
    public function user() { return $this->belongsTo(User::class); }
    public function getDiaChiDayDuAttribute() {
        return $this->dia_chi_chi_tiet . ', ' . $this->quan_huyen . ', ' . $this->tinh_thanh;
    }
}