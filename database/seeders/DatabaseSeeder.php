<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        DB::table('users')->insert([
            [
                'ho_ten' => 'Chủ Cửa Hàng HV',
                'so_dien_thoai' => '0900000001',
                'mat_khau' => Hash::make('password'),
                'email' => 'admin@hvpetshop.com',
                'vai_tro' => 'chu_cua_hang',
                'trang_thai' => true,
                'mat_khau_mac_dinh' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ho_ten' => 'Nguyễn Văn Nhân Viên',
                'so_dien_thoai' => '0900000002',
                'mat_khau' => Hash::make('1111'),
                'email' => 'nhanvien@hvpetshop.com',
                'vai_tro' => 'nhan_vien',
                'trang_thai' => true,
                'mat_khau_mac_dinh' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ho_ten' => 'Trần Thị Khách Hàng',
                'so_dien_thoai' => '0900000003',
                'mat_khau' => Hash::make('password'),
                'email' => 'khachhang@gmail.com',
                'vai_tro' => 'khach_hang',
                'trang_thai' => true,
                'mat_khau_mac_dinh' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // DanhMuc
        DB::table('danh_mucs')->insert([
            ['ten_danh_muc' => 'Thức ăn cho Chó', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
            ['ten_danh_muc' => 'Thức ăn cho Mèo', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
            ['ten_danh_muc' => 'Đồ chơi', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
            ['ten_danh_muc' => 'Phụ kiện', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
            ['ten_danh_muc' => 'Nệm & Chuồng', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
            ['ten_danh_muc' => 'Vệ sinh', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // NhaCungCap
        DB::table('nha_cung_caps')->insert([
            ['ten_ncc' => 'Royal Canin Vietnam', 'so_dien_thoai' => '0281234567', 'email' => 'royalcanin@vn.com', 'dia_chi' => 'HCM', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
            ['ten_ncc' => 'Whiskas Vietnam', 'so_dien_thoai' => '0281234568', 'email' => 'whiskas@vn.com', 'dia_chi' => 'HN', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
            ['ten_ncc' => 'PetJoy', 'so_dien_thoai' => '0281234569', 'email' => 'petjoy@vn.com', 'dia_chi' => 'DN', 'trang_thai' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // SanPham
        $products = [
            ['ma_sp' => 'SP001', 'danh_muc_id' => 1, 'nha_cung_cap_id' => 1, 'ten_sp' => 'Hạt Dinh Dưỡng Cao Cấp Cho Chó Con HV-Pro', 'mo_ta' => 'HV-Pro được phát triển bởi các chuyên gia dinh dưỡng hàng đầu, cung cấp đầy đủ DHA, Canxi và Vitamin thiết yếu giúp chó con phát triển xương khớp và trí não vượt trội. Phù hợp cho chó con từ 2–12 tháng tuổi.', 'gia' => 350000, 'so_luong' => 50, 'so_luong_kho' => 50],
            ['ma_sp' => 'SP002', 'danh_muc_id' => 2, 'nha_cung_cap_id' => 2, 'ten_sp' => 'Thức Ăn Ướt Pate Whiskas Cho Mèo Vị Cá Thu', 'mo_ta' => 'Pate Whiskas thơm ngon với vị cá thu tươi, bổ sung đầy đủ dinh dưỡng cho mèo của bạn.', 'gia' => 45000, 'so_luong' => 100, 'so_luong_kho' => 100],
            ['ma_sp' => 'SP003', 'danh_muc_id' => 3, 'nha_cung_cap_id' => 3, 'ten_sp' => 'Bộ Đồ Chơi Gặm Nướu Cho Chó', 'mo_ta' => 'Bộ đồ chơi gặm nướu giúp chó giải tỏa căng thẳng và làm sạch răng.', 'gia' => 120000, 'so_luong' => 30, 'so_luong_kho' => 30],
            ['ma_sp' => 'SP004', 'danh_muc_id' => 6, 'nha_cung_cap_id' => 3, 'ten_sp' => 'Lược Chải Lông Chuyên Dụng', 'mo_ta' => 'Lược chải lông chuyên dụng giúp loại bỏ lông rụng và massage cho thú cưng.', 'gia' => 85000, 'so_luong' => 40, 'so_luong_kho' => 40],
            ['ma_sp' => 'SP005', 'danh_muc_id' => 4, 'nha_cung_cap_id' => 3, 'ten_sp' => 'Túi Vận Chuyển Cao Cấp', 'mo_ta' => 'Túi vận chuyển thú cưng cao cấp, thoáng khí, bền đẹp.', 'gia' => 550000, 'so_luong' => 15, 'so_luong_kho' => 15],
            ['ma_sp' => 'SP006', 'danh_muc_id' => 1, 'nha_cung_cap_id' => 1, 'ten_sp' => 'Royal Canin Maxi Puppy 15kg', 'mo_ta' => 'Thức ăn cao cấp cho chó lớn giống lớn, giai đoạn con.', 'gia' => 1450000, 'so_luong' => 20, 'so_luong_kho' => 20],
            ['ma_sp' => 'SP007', 'danh_muc_id' => 4, 'nha_cung_cap_id' => 3, 'ten_sp' => 'Dây Xích Bản To Cao Cấp', 'mo_ta' => 'Dây xích bản to cao cấp màu xanh navy, chắc chắn và thời trang.', 'gia' => 85000, 'so_luong' => 25, 'so_luong_kho' => 25],
            ['ma_sp' => 'SP008', 'danh_muc_id' => 5, 'nha_cung_cap_id' => 3, 'ten_sp' => 'Nệm Nhung Sang Trọng Cho Thú Cưng', 'mo_ta' => 'Nệm nhung mềm mại, ấm áp cho thú cưng ngủ ngon.', 'gia' => 420000, 'so_luong' => 18, 'so_luong_kho' => 18],
            ['ma_sp' => 'SP009', 'danh_muc_id' => 3, 'nha_cung_cap_id' => 3, 'ten_sp' => 'Máy Laser Tự Động Thông Minh', 'mo_ta' => 'Máy bắn tia laser tự động giúp mèo vui chơi mà không cần chủ.', 'gia' => 550000, 'so_luong' => 10, 'so_luong_kho' => 10],
            ['ma_sp' => 'SP010', 'danh_muc_id' => 4, 'nha_cung_cap_id' => 3, 'ten_sp' => 'Dây Dắt Yếm Cao Cấp Cho Chó', 'mo_ta' => 'Dây dắt yếm thoải mái, không gây đau cổ cho thú cưng.', 'gia' => 185000, 'so_luong' => 22, 'so_luong_kho' => 22],
        ];

        foreach ($products as $p) {
            DB::table('san_phams')->insert(array_merge($p, [
                'nguong_canh_bao' => 5,
                'trang_thai' => true,
                'la_moi' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // KhuyenMai
        DB::table('khuyen_mais')->insert([
            [
                'ma_km' => 'HVWELCOME',
                'ten_chuong_trinh' => 'Chào mừng khách mới',
                'ty_le_giam' => 10,
                'so_tien_giam' => 0,
                'loai' => 'phan_tram',
                'don_hang_toi_thieu' => 200000,
                'giam_toi_da' => 50000,
                'mien_phi_ship_tu' => null,
                'so_luong_ma' => 100,
                'so_lan_da_dung' => 0,
                'gioi_han_moi_kh' => 1,
                'ngay_bat_dau' => Carbon::now()->subDays(30),
                'ngay_ket_thuc' => Carbon::now()->addDays(60),
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_km' => 'FREESHIP',
                'ten_chuong_trinh' => 'Miễn phí vận chuyển',
                'ty_le_giam' => 0,
                'so_tien_giam' => 0,
                'loai' => 'mien_phi_ship',
                'don_hang_toi_thieu' => 500000,
                'giam_toi_da' => null,
                'mien_phi_ship_tu' => 500000,
                'so_luong_ma' => null,
                'so_lan_da_dung' => 0,
                'gioi_han_moi_kh' => null,
                'ngay_bat_dau' => Carbon::now()->subDays(10),
                'ngay_ket_thuc' => Carbon::now()->addDays(90),
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_km' => 'SALE50K',
                'ten_chuong_trinh' => 'Giảm 50.000đ đơn từ 300k',
                'ty_le_giam' => 0,
                'so_tien_giam' => 50000,
                'loai' => 'co_dinh',
                'don_hang_toi_thieu' => 300000,
                'giam_toi_da' => null,
                'mien_phi_ship_tu' => null,
                'so_luong_ma' => 50,
                'so_lan_da_dung' => 0,
                'gioi_han_moi_kh' => 2,
                'ngay_bat_dau' => Carbon::now()->subDays(5),
                'ngay_ket_thuc' => Carbon::now()->addDays(30),
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Dia chi mau
        DB::table('dia_chis')->insert([
            [
                'user_id' => 3,
                'ho_ten' => 'Trần Thị Khách Hàng',
                'so_dien_thoai' => '0900000003',
                'dia_chi_chi_tiet' => '123 Đường ABC',
                'tinh_thanh' => 'TP. Hồ Chí Minh',
                'quan_huyen' => 'Quận 1',
                'la_mac_dinh' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}