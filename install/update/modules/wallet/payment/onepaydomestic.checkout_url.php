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

$SECURE_SECRET = $payment_config['secure_secret'];
$vpcURL = $payment_config['virtualPaymentClientURL'] . "?";

$array_post = array();
$array_post['Title'] = $global_config['site_name']; // Site title
$array_post['vpc_Merchant'] = $payment_config['vpc_Merchant']; // Merchant ID
$array_post['vpc_AccessCode'] = $payment_config['vpc_AccessCode']; // Merchant AccessCode
$array_post['vpc_Version'] = $payment_config['vpc_Version']; // Phien ban
$array_post['vpc_Command'] = $payment_config['vpc_Command']; // Pay
$array_post['vpc_Locale'] = $payment_config['vpc_Locale']; // Viet Nam
$array_post['vpc_Currency'] = 'VND'; // Viet Nam Dong

$array_post['vpc_MerchTxnRef'] = nv_genpass(20); // ID giao dich tu tang
$array_post['vpc_OrderInfo'] = $post['transaction_code']; // Ten hoa don
$array_post['vpc_Amount'] = 100 * intval($post['money_net']); // So tien can thanh toan

$array_post['vpc_ReturnURL'] = $post['ReturnURL']; // URL tra ve
$array_post['vpc_TicketNo'] = $client_info['ip']; // IP nguoi mua
$array_post['vpc_Customer_Phone'] = $post['customer_phone']; // Dien thoai nguoi mua
$array_post['vpc_Customer_Email'] = $post['customer_email']; // Email nguoi mua

$stringHashData = "";
ksort($array_post);

$appendAmp = 0;

foreach ($array_post as $key => $value) {
    if (strlen($value) > 0) {
        if ($appendAmp == 0) {
            $vpcURL .= urlencode($key) . '=' . urlencode($value);
            $appendAmp = 1;
        } else {
            $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
        }

        if ((strlen($value) > 0) and ((substr($key, 0, 4) == "vpc_") or (substr($key, 0, 5) == "user_"))) {
            $stringHashData .= $key . "=" . $value . "&";
        }
    }
}

$stringHashData = rtrim($stringHashData, "&");

if (strlen($SECURE_SECRET) > 0) {
    $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $SECURE_SECRET)));
}

$url = $vpcURL;
