<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return $this->redirectByRole(Auth::user());
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'so_dien_thoai' => 'required',
            'mat_khau' => 'required',
        ], [
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $user = User::where('so_dien_thoai', $request->so_dien_thoai)->first();

        if (!$user || !Hash::check($request->mat_khau, $user->mat_khau)) {
            return back()->withErrors(['so_dien_thoai' => 'Số điện thoại hoặc mật khẩu không đúng'])->withInput();
        }

        if (!$user->trang_thai) {
            return back()->withErrors(['so_dien_thoai' => 'Tài khoản đã bị khóa. Vui lòng liên hệ admin.'])->withInput();
        }

        Auth::login($user, $request->has('remember'));

        // Nếu nhân viên dùng mật khẩu mặc định -> bắt đổi mật khẩu
        if ($user->mat_khau_mac_dinh) {
            return redirect()->route('doi-mat-khau')->with('warning', 'Vui lòng đổi mật khẩu mặc định trước khi sử dụng!');
        }

        return $this->redirectByRole($user);
    }

    private function redirectByRole(User $user)
    {
        if ($user->isChuCuaHang() || $user->isNhanVien()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'ho_ten' => 'required|min:2',
            'so_dien_thoai' => 'required|unique:users,so_dien_thoai|regex:/^[0-9]{10,11}$/',
            'email' => 'nullable|email|unique:users,email',
            'mat_khau' => 'required|min:6|confirmed',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ tên',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại',
            'so_dien_thoai.unique' => 'Số điện thoại đã tồn tại',
            'so_dien_thoai.regex' => 'Số điện thoại không hợp lệ',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'mat_khau.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $user = User::create([
            'ho_ten' => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'email' => $request->email,
            'mat_khau' => Hash::make($request->mat_khau),
            'vai_tro' => 'khach_hang',
            'trang_thai' => true,
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với HV Pet Shop.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email'], ['email.required' => 'Vui lòng nhập email', 'email.email' => 'Email không hợp lệ']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống']);
        }

        $token = Str::random(64);
        $user->update([
            'reset_token' => $token,
            'reset_token_expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Trong môi trường thực tế sẽ gửi email
        // Mail::to($user->email)->send(new ResetPasswordMail($token));

        return back()->with('success', 'Link đặt lại mật khẩu đã được gửi đến email của bạn! (Hiệu lực 15 phút)');
    }

    public function showResetPassword($token)
    {
        $user = User::where('reset_token', $token)
            ->where('reset_token_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return redirect()->route('forgot-password')->withErrors(['token' => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn!']);
        }

        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'mat_khau' => 'required|min:6|confirmed',
        ]);

        $user = User::where('reset_token', $token)
            ->where('reset_token_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return redirect()->route('forgot-password')->withErrors(['token' => 'Link đã hết hạn!']);
        }

        $user->update([
            'mat_khau' => Hash::make($request->mat_khau),
            'reset_token' => null,
            'reset_token_expires_at' => null,
        ]);

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công!');
    }

    public function showDoiMatKhau()
    {
        return view('auth.doi-mat-khau');
    }

    public function doiMatKhau(Request $request)
    {
        $request->validate([
            'mat_khau_cu' => 'required',
            'mat_khau_moi' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->mat_khau_cu, $user->mat_khau)) {
            return back()->withErrors(['mat_khau_cu' => 'Mật khẩu hiện tại không đúng']);
        }

        if ($request->mat_khau_moi === $request->mat_khau_cu) {
            return back()->withErrors(['mat_khau_moi' => 'Mật khẩu mới không được trùng mật khẩu cũ']);
        }

        $user->update([
            'mat_khau' => Hash::make($request->mat_khau_moi),
            'mat_khau_mac_dinh' => false,
        ]);

        return redirect()->route('home')->with('success', 'Đổi mật khẩu thành công!');
    }
}