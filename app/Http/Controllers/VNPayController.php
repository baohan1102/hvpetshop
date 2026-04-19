<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\LichSuDonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VNPayController extends Controller
{
    public function createPayment(Request $request, $donHangId)
    {
        $donHang = DonHang::findOrFail($donHangId);
        if ($donHang->user_id !== Auth::id()) abort(403);

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_TmnCode    = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url        = config('vnpay.url');
        $vnp_ReturnUrl  = config('vnpay.return_url');
        $vnp_TxnRef     = $donHang->ma_dh . '_' . time();
        $vnp_Amount     = $donHang->thanh_tien * 100;
        $vnp_CreateDate = date('YmdHis');

        $inputData = [
            'vnp_Version'   => '2.1.0',
            'vnp_TmnCode'   => $vnp_TmnCode,
            'vnp_Amount'    => $vnp_Amount,
            'vnp_Command'   => 'pay',
            'vnp_CreateDate'=> $vnp_CreateDate,
            'vnp_CurrCode'  => 'VND',
            'vnp_IpAddr'    => $request->ip(),
            'vnp_Locale'    => 'vn',
            'vnp_OrderInfo' => 'Thanh toan don hang ' . $donHang->ma_dh,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $vnp_ReturnUrl,
            'vnp_TxnRef'    => $vnp_TxnRef,
            // ĐÃ XÓA vnp_ExpireDate
        ];

        ksort($inputData);

        $hashData = '';
        $query    = '';
        foreach ($inputData as $key => $value) {
            $hashData .= ($hashData ? '&' : '') . urlencode($key) . '=' . urlencode($value);
            $query    .= ($query    ? '&' : '') . urlencode($key) . '=' . urlencode($value);
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $paymentUrl    = $vnp_Url . '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect($paymentUrl);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        $inputData      = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        ksort($inputData);

        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= ($hashData ? '&' : '') . urlencode($key) . '=' . urlencode($value);
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $txnRef  = $inputData['vnp_TxnRef'] ?? '';
        $maDH    = explode('_', $txnRef)[0];
        $donHang = DonHang::where('ma_dh', $maDH)->first();

        if (!$donHang) {
            return redirect()->route('don-hang.lich-su')
                ->withErrors(['Không tìm thấy đơn hàng!']);
        }

        if ($secureHash !== $vnp_SecureHash) {
            return redirect()->route('don-hang.chi-tiet', $donHang->id)
                ->withErrors(['Chữ ký không hợp lệ!']);
        }

        $responseCode = $inputData['vnp_ResponseCode'] ?? '';

        if ($responseCode === '00') {
            $donHang->update(['trang_thai' => 'da_xac_nhan']);

            LichSuDonHang::create([
                'don_hang_id'   => $donHang->id,
                'trang_thai'    => 'da_xac_nhan',
                'nhan_xet'      => 'Thanh toán VNPAY thành công. Mã GD: ' . ($inputData['vnp_TransactionNo'] ?? ''),
                'thuc_hien_boi' => $donHang->user_id,
            ]);

            return redirect()->route('don-hang.chi-tiet', $donHang->id)
                ->with('success', '✅ Thanh toán VNPAY thành công! Mã GD: ' . ($inputData['vnp_TransactionNo'] ?? ''));

        } else {
            $errors = [
                '24' => 'Bạn đã hủy giao dịch.',
                '51' => 'Tài khoản không đủ số dư.',
                '11' => 'Hết hạn chờ thanh toán.',
                '12' => 'Thẻ bị khóa.',
                '13' => 'Sai mật khẩu OTP.',
                '65' => 'Vượt hạn mức giao dịch.',
                '75' => 'Ngân hàng đang bảo trì.',
            ];

            $msg = $errors[$responseCode] ?? 'Thanh toán thất bại! Mã lỗi: ' . $responseCode;

            return redirect()->route('don-hang.chi-tiet', $donHang->id)
                ->withErrors([$msg]);
        }
    }

    public function vnpayIPN(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        $inputData      = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        ksort($inputData);

        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= ($hashData ? '&' : '') . urlencode($key) . '=' . urlencode($value);
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid Checksum']);
        }

        $txnRef  = $inputData['vnp_TxnRef'] ?? '';
        $maDH    = explode('_', $txnRef)[0];
        $donHang = DonHang::where('ma_dh', $maDH)->first();

        if (!$donHang) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        if (($inputData['vnp_ResponseCode'] ?? '') === '00') {
            $donHang->update(['trang_thai' => 'da_xac_nhan']);
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }
}