<?php
return [
    'tmn_code'    => env('VNPAY_TMN_CODE', 'DEJE6R68'),
    'hash_secret' => env('VNPAY_HASH_SECRET', 'UGWZ7VQLQ44X9BGKHRI9ZZ0PVRPJLLBA'),
    'url'         => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url'  => env('VNPAY_RETURN_URL', 'http://localhost:8000/vnpay/return'),
];