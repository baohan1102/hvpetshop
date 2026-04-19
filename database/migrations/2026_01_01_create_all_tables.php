<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users table (NguoiDung - chủ cửa hàng, nhân viên, khách hàng)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->string('so_dien_thoai')->unique();
            $table->string('mat_khau');
            $table->string('email')->nullable()->unique();
            $table->date('ngay_sinh')->nullable();
            $table->string('dia_chi')->nullable();
            $table->enum('vai_tro', ['chu_cua_hang', 'nhan_vien', 'khach_hang'])->default('khach_hang');
            $table->boolean('trang_thai')->default(true);
            $table->boolean('mat_khau_mac_dinh')->default(false);
            $table->string('remember_token')->nullable();
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_token_expires_at')->nullable();
            $table->timestamps();
        });

        // DanhMuc
        Schema::create('danh_mucs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_danh_muc');
            $table->boolean('trang_thai')->default(true); // true=hiện, false=ẩn
            $table->integer('so_lan_an_dm')->default(0);
            $table->timestamp('ngay_xoa_dm')->nullable();
            $table->timestamps();
        });

        // NhaCungCap
        Schema::create('nha_cung_caps', function (Blueprint $table) {
            $table->id();
            $table->string('ten_ncc');
            $table->string('so_dien_thoai')->nullable();
            $table->string('email')->nullable();
            $table->string('dia_chi')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });

        // SanPham
        Schema::create('san_phams', function (Blueprint $table) {
            $table->id();
            $table->string('ma_sp')->unique();
            $table->foreignId('danh_muc_id')->constrained('danh_mucs');
            $table->foreignId('nha_cung_cap_id')->nullable()->constrained('nha_cung_caps')->nullOnDelete();
            $table->string('ten_sp');
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->decimal('gia', 15, 2);
            $table->integer('so_luong')->default(0);
            $table->integer('so_luong_kho')->default(0);
            $table->integer('nguong_canh_bao')->default(5);
            $table->boolean('trang_thai')->default(true); // true=hiển thị, false=ẩn
            $table->boolean('la_moi')->default(true);
            $table->timestamps();
        });

        // KhuyenMai (Voucher/Mã giảm giá)
        Schema::create('khuyen_mais', function (Blueprint $table) {
            $table->id();
            $table->string('ma_km')->unique();
            $table->string('ten_chuong_trinh');
            $table->decimal('ty_le_giam', 5, 2)->default(0); // % giảm
            $table->decimal('so_tien_giam', 15, 2)->default(0); // số tiền giảm cố định
            $table->enum('loai', ['phan_tram', 'co_dinh', 'mien_phi_ship'])->default('phan_tram');
            $table->decimal('don_hang_toi_thieu', 15, 2)->default(0);
            $table->decimal('giam_toi_da', 15, 2)->nullable(); // giảm tối đa khi dùng %
            $table->decimal('mien_phi_ship_tu', 15, 2)->nullable(); // miễn phí ship khi đơn >= x
            $table->integer('so_luong_ma')->nullable(); // tổng số mã
            $table->integer('so_lan_da_dung')->default(0);
            $table->integer('gioi_han_moi_kh')->nullable(); // mỗi KH dùng tối đa x lần
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });

        // GioHang
        Schema::create('gio_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->integer('so_luong')->default(1);
            $table->decimal('gia', 15, 2);
            $table->timestamps();
        });

        // DonHang
        Schema::create('don_hangs', function (Blueprint $table) {
            $table->id();
            $table->string('ma_dh')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('nhan_vien_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('khuyen_mai_id')->nullable()->constrained('khuyen_mais')->nullOnDelete();
            $table->decimal('tong_tien', 15, 2);
            $table->decimal('phi_van_chuyen', 15, 2)->default(35000);
            $table->decimal('tien_giam', 15, 2)->default(0);
            $table->decimal('thanh_tien', 15, 2);
            $table->string('dia_chi_giao');
            $table->string('so_dien_thoai_nhan');
            $table->string('ho_ten_nhan');
            $table->enum('phuong_thuc_tt', ['chuyen_khoan', 'cod', 'momo', 'visa'])->default('cod');
            $table->enum('trang_thai', [
                'cho_xac_nhan',
                'da_xac_nhan',
                'dang_giao',
                'da_hoan_thanh',
                'da_huy'
            ])->default('cho_xac_nhan');
            $table->timestamp('ngay_dat');
            $table->timestamp('ngay_giao_du_kien')->nullable();
            $table->timestamp('ngay_giao_thuc_te')->nullable();
            $table->text('ly_do_huy')->nullable();
            $table->boolean('da_nhan_hang')->default(false);
            $table->timestamps();
        });

        // ChiTietDonHang
        Schema::create('chi_tiet_don_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('don_hang_id')->constrained('don_hangs')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams');
            $table->integer('so_luong');
            $table->decimal('gia', 15, 2);
            $table->decimal('thanh_tien', 15, 2);
            $table->timestamps();
        });

        // LichSuDonHang
        Schema::create('lich_su_don_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('don_hang_id')->constrained('don_hangs')->onDelete('cascade');
            $table->string('trang_thai');
            $table->string('danh_gia')->nullable();
            $table->string('nhan_xet')->nullable();
            $table->string('ly_do_huy')->nullable();
            $table->foreignId('thuc_hien_boi')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // DanhGiaSanPham
        Schema::create('danh_gia_san_phams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('san_pham_id')->constrained('san_phams');
            $table->foreignId('don_hang_id')->constrained('don_hangs');
            $table->integer('so_sao')->unsigned()->default(5);
            $table->text('nhan_xet')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->boolean('da_duyet')->default(true);
            $table->timestamps();
        });

        // PhieuNhapKho
        Schema::create('phieu_nhap_khos', function (Blueprint $table) {
            $table->id();
            $table->string('ma_nk')->unique();
            $table->foreignId('san_pham_id')->constrained('san_phams');
            $table->foreignId('nha_cung_cap_id')->nullable()->constrained('nha_cung_caps')->nullOnDelete();
            $table->foreignId('nguoi_tao_id')->constrained('users');
            $table->integer('so_luong');
            $table->decimal('gia_nhap', 15, 2);
            $table->decimal('tong_tien', 15, 2);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });

        // DiaChi (multiple addresses per user)
        Schema::create('dia_chis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ho_ten');
            $table->string('so_dien_thoai');
            $table->string('dia_chi_chi_tiet');
            $table->string('tinh_thanh');
            $table->string('quan_huyen');
            $table->boolean('la_mac_dinh')->default(false);
            $table->timestamps();
        });

        // BaoCao (lưu báo cáo được tạo)
        Schema::create('bao_caos', function (Blueprint $table) {
            $table->id();
            $table->string('ma_bc')->unique();
            $table->string('thong_tin_bc');
            $table->timestamp('ngay_tao');
            $table->integer('nguong_canh_bao')->default(0);
            $table->timestamps();
        });

        // KhuyenMaiSuDung (theo dõi KH đã dùng mã nào)
        Schema::create('khuyen_mai_su_dungs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('khuyen_mai_id')->constrained('khuyen_mais');
            $table->foreignId('don_hang_id')->nullable()->constrained('don_hangs')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khuyen_mai_su_dungs');
        Schema::dropIfExists('bao_caos');
        Schema::dropIfExists('dia_chis');
        Schema::dropIfExists('phieu_nhap_khos');
        Schema::dropIfExists('danh_gia_san_phams');
        Schema::dropIfExists('lich_su_don_hangs');
        Schema::dropIfExists('chi_tiet_don_hangs');
        Schema::dropIfExists('don_hangs');
        Schema::dropIfExists('gio_hangs');
        Schema::dropIfExists('khuyen_mais');
        Schema::dropIfExists('san_phams');
        Schema::dropIfExists('nha_cung_caps');
        Schema::dropIfExists('danh_mucs');
        Schema::dropIfExists('users');
    }
};