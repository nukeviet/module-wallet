<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET'))
    die('Stop!!!');

$vnp_TxnRef = $post['transaction_code']; // Mã đơn hàng
$vnp_OrderInfo = nv_substr(nv_EncString($post['transaction_info']), 0, 255); // Thông tin thanh toán, không dấu
$vnp_OrderType = 'other'; // Thanh toán loại khác
$vnp_Amount = $post['money_net'] * 100; // Số tiền thực nhân với 100
$vnp_Locale = (in_array(NV_LANG_INTERFACE, array('vi', 'en')) ? NV_LANG_INTERFACE : 'en'); // VNPay hỗ trợ ngôn ngữ Việt hoặc Anh
$vnp_BankCode = ''; // Mã ngân hàng, để trống để khách chọn tại trang thanh toán
$vnp_IpAddr = $client_info['ip']; // IP của khách hàng giao dịch
$vnp_Returnurl = $post['ReturnURL'];

$inputData = array(
    "vnp_Version" => "2.0.0",
    "vnp_TmnCode" => $payment_config['vnp_TmnCode'],
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => $payment_config['vnp_Command'],
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => $payment_config['vnp_CurrCode'],
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef,
);

if (isset($vnp_BankCode) and $vnp_BankCode != "") {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}
ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . $key . "=" . $value;
    } else {
        $hashdata .= $key . "=" . $value;
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $payment_config['vnp_Url'] . "?" . $query;
if (isset($payment_config['vnp_HashSecret'])) {
    $vnpSecureHash = md5($payment_config['vnp_HashSecret'] . $hashdata);
    $vnp_Url .= 'vnp_SecureHashType=MD5&vnp_SecureHash=' . $vnpSecureHash;
}

$url = $vnp_Url;
