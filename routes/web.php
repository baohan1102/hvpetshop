<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\TaiKhoanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SanPhamController;
use App\Http\Controllers\Admin\DonHangAdminController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\NhaCungCapController;
use App\Http\Controllers\Admin\DanhMucController;
use App\Http\Controllers\Admin\NhanVienController;
use App\Http\Controllers\Admin\KhoController;
use App\Http\Controllers\Admin\BaoCaoController;
use App\Http\Controllers\Admin\KhachHangController;
use App\Http\Controllers\VNPayController;
// VNPAY
Route::middleware('auth')->group(function () {
    Route::get('/vnpay/payment/{donHangId}', [VNPayController::class, 'createPayment'])->name('vnpay.payment');
});
Route::get('/vnpay/return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
Route::post('/vnpay/ipn', [VNPayController::class, 'vnpayIPN'])->name('vnpay.ipn');
// ============================
// AUTH
// ============================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password');
Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword']);
Route::get('/doi-mat-khau', [AuthController::class, 'showDoiMatKhau'])->name('doi-mat-khau')->middleware('auth');
Route::post('/doi-mat-khau', [AuthController::class, 'doiMatKhau'])->middleware('auth');

// ============================
// PUBLIC - FRONTEND
// ============================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham', [HomeController::class, 'danhSachSanPham'])->name('san-pham.danh-sach');
Route::get('/san-pham/{id}', [HomeController::class, 'chiTietSanPham'])->name('san-pham.chi-tiet');
Route::get('/tim-kiem', [HomeController::class, 'timKiem'])->name('tim-kiem');

// ============================
// GIỎ HÀNG
// ============================
Route::middleware('auth')->prefix('gio-hang')->name('gio-hang.')->group(function () {
    Route::get('/', [GioHangController::class, 'index'])->name('index');
    Route::post('/them/{id}', [GioHangController::class, 'them'])->name('them');
    Route::put('/cap-nhat/{id}', [GioHangController::class, 'capNhatSoLuong'])->name('cap-nhat');
    Route::delete('/xoa/{id}', [GioHangController::class, 'xoa'])->name('xoa');
    Route::delete('/xoa-tat-ca', [GioHangController::class, 'xoaTatCa'])->name('xoa-tat-ca');
    Route::post('/ap-dung-km', [GioHangController::class, 'apDungKhuyenMai'])->name('ap-dung-km');
    Route::post('/prepare-checkout', [GioHangController::class, 'prepareCheckout'])->name('prepare-checkout');
});
Route::get('/gio-hang', [GioHangController::class, 'index'])->name('gio-hang');
Route::get('/api/gio-hang/count', [GioHangController::class, 'demGioHang']);

// ============================
// ĐƠN HÀNG
// ============================
Route::middleware('auth')->prefix('don-hang')->name('don-hang.')->group(function () {
    Route::get('/checkout', [DonHangController::class, 'checkout'])->name('checkout');
    Route::post('/dat-hang', [DonHangController::class, 'datHang'])->name('dat-hang');
    Route::get('/xac-nhan/{id}', [DonHangController::class, 'xacNhan'])->name('xac-nhan');
    Route::get('/lich-su', [DonHangController::class, 'lichSu'])->name('lich-su');
    Route::get('/{id}', [DonHangController::class, 'chiTiet'])->name('chi-tiet');
    Route::post('/{id}/huy', [DonHangController::class, 'huy'])->name('huy');
    Route::post('/{id}/nhan-hang', [DonHangController::class, 'nhanHang'])->name('nhan-hang');
    Route::post('/{id}/danh-gia', [DonHangController::class, 'danhGia'])->name('danh-gia');
});

// ============================
// TÀI KHOẢN
// ============================
Route::middleware('auth')->prefix('tai-khoan')->name('tai-khoan.')->group(function () {
    Route::get('/', [TaiKhoanController::class, 'index'])->name('index');
    Route::put('/cap-nhat', [TaiKhoanController::class, 'capNhat'])->name('cap-nhat');
    Route::post('/dia-chi', [TaiKhoanController::class, 'themDiaChi'])->name('dia-chi.them');
    Route::put('/dia-chi/{id}/mac-dinh', [TaiKhoanController::class, 'datMacDinhDiaChi'])->name('dia-chi.mac-dinh');
    Route::delete('/dia-chi/{id}', [TaiKhoanController::class, 'xoaDiaChi'])->name('dia-chi.xoa');
});

// ============================
// ADMIN
// ============================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Sản phẩm
  Route::get('/san-pham', [SanPhamController::class, 'index'])->name('san-pham.index');
Route::get('/san-pham/create', [SanPhamController::class, 'create'])->name('san-pham.create');
Route::post('/san-pham', [SanPhamController::class, 'store'])->name('san-pham.store');
Route::get('/san-pham/{id}', [SanPhamController::class, 'show'])->name('san-pham.show');
Route::get('/san-pham/{id}/edit', [SanPhamController::class, 'edit'])->name('san-pham.edit');
Route::put('/san-pham/{id}', [SanPhamController::class, 'update'])->name('san-pham.update');

Route::delete('/san-pham/{id}', [SanPhamController::class, 'destroy'])->name('san-pham.destroy');

Route::post('/san-pham/{id}/toggle', [SanPhamController::class, 'toggleTrangThai'])->name('san-pham.toggle');
    // Danh mục
    Route::get('/danh-muc', [DanhMucController::class, 'index'])->name('danh-muc.index');
    Route::post('/danh-muc', [DanhMucController::class, 'store'])->name('danh-muc.store');
    Route::put('/danh-muc/{id}', [DanhMucController::class, 'update'])->name('danh-muc.update');
    Route::post('/danh-muc/{id}/toggle', [DanhMucController::class, 'toggleTrangThai'])->name('danh-muc.toggle');

    // Đơn hàng
    Route::get('/don-hang', [DonHangAdminController::class, 'index'])->name('don-hang.index');
    Route::get('/don-hang/{id}', [DonHangAdminController::class, 'show'])->name('don-hang.show');
    Route::put('/don-hang/{id}/trang-thai', [DonHangAdminController::class, 'capNhatTrangThai'])->name('don-hang.trang-thai');

  // Khuyến mãi
Route::get('/khuyen-mai', [KhuyenMaiController::class, 'index'])->name('khuyen-mai.index');

Route::get('/khuyen-mai/create', [KhuyenMaiController::class, 'create'])->name('khuyen-mai.create');

Route::post('/khuyen-mai', [KhuyenMaiController::class, 'store'])->name('khuyen-mai.store');

Route::get('/khuyen-mai/{id}/edit', [KhuyenMaiController::class, 'edit'])->name('khuyen-mai.edit');

Route::put('/khuyen-mai/{id}', [KhuyenMaiController::class, 'update'])->name('khuyen-mai.update');

Route::delete('/khuyen-mai/{id}', [KhuyenMaiController::class, 'destroy'])->name('khuyen-mai.destroy');

// 👇 thêm dòng này để tránh lỗi 404
Route::get('/khuyen-mai/{id}', [KhuyenMaiController::class, 'show'])->name('khuyen-mai.show');
    Route::get('/nha-cung-cap', [NhaCungCapController::class, 'index'])->name('nha-cung-cap.index');
    Route::get('/nha-cung-cap/create', [NhaCungCapController::class, 'create'])->name('nha-cung-cap.create');
    Route::post('/nha-cung-cap', [NhaCungCapController::class, 'store'])->name('nha-cung-cap.store');
    Route::get('/nha-cung-cap/{id}/edit', [NhaCungCapController::class, 'edit'])->name('nha-cung-cap.edit');
    Route::put('/nha-cung-cap/{id}', [NhaCungCapController::class, 'update'])->name('nha-cung-cap.update');
    Route::delete('/nha-cung-cap/{id}', [NhaCungCapController::class, 'destroy'])->name('nha-cung-cap.destroy');

    // Nhân viên
    Route::get('/nhan-vien', [NhanVienController::class, 'index'])->name('nhan-vien.index');
    Route::post('/nhan-vien', [NhanVienController::class, 'store'])->name('nhan-vien.store');
    Route::post('/nhan-vien/{id}/cap-lai-mat-khau', [NhanVienController::class, 'capLaiMatKhau'])->name('nhan-vien.cap-lai-mat-khau');
    Route::post('/nhan-vien/{id}/toggle', [NhanVienController::class, 'toggleTrangThai'])->name('nhan-vien.toggle');

    // Kho
    Route::get('/kho', [KhoController::class, 'index'])->name('kho.index');
    Route::post('/kho/nhap', [KhoController::class, 'nhapKho'])->name('kho.nhap');
    Route::get('/kho/thong-ke', [KhoController::class, 'thongKe'])->name('kho.thong-ke');

    // Báo cáo
    Route::get('/bao-cao', [BaoCaoController::class, 'index'])->name('bao-cao.index');

    // Khách hàng
    Route::get('/khach-hang', [KhachHangController::class, 'index'])->name('khach-hang.index');
    Route::get('/khach-hang/{id}', [KhachHangController::class, 'show'])->name('khach-hang.show');
});
