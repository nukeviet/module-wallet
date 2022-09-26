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

/**
 * null2unknown()
 *
 * @param mixed $data
 * @return
 */
function null2unknown($data)
{
    return $data == "" ? "No Value Returned" : $data;
}

$SECURE_SECRET = $payment_config['secure_secret'];
$vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
unset($_GET["vpc_SecureHash"]);

if (strlen($SECURE_SECRET) > 0 and $_GET["vpc_TxnResponseCode"] != "7" and $_GET["vpc_TxnResponseCode"] != "No Value Returned") {
    $stringHashData = "";

    foreach ($_GET as $key => $value) {
        if ($key != "vpc_SecureHash" and (strlen($value) > 0) and ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
            $stringHashData .= $key . "=" . $value . "&";
        }
    }

    $stringHashData = rtrim($stringHashData, "&");

    if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $SECURE_SECRET)))) {
        $hashValidated = "CORRECT";
    } else {
        $hashValidated = "INVALID HASH";
    }
} else {
    $hashValidated = "INVALID HASH";
}

if ($hashValidated != "CORRECT") {
    $error = $lang_module['pay_error_checkhash'];
} else {
    $amount = null2unknown($_GET["vpc_Amount"]); // So tien thanh toan
    $orderInfo = null2unknown($_GET["vpc_OrderInfo"]); // Ma hoa don (ID dat hang)
    $txnResponseCode = null2unknown($_GET["vpc_TxnResponseCode"]); // Ma tra ve
    $vpc_MerchTxnRef = null2unknown($_GET["vpc_MerchTxnRef"]); // Ma giao dich do OnePage Sinh ra dung de QueryDR sau nay
    $payment_id = (int)$_GET['vpc_TransactionNo'];

    if ($hashValidated == "CORRECT" and $txnResponseCode == "0") {
        // Giao dich thanh cong
        $nv_transaction_status = 4;
    } elseif ($hashValidated == "CORRECT" and $txnResponseCode == "99") {
        // Hủy giao dịch
        $nv_transaction_status = -1;
    } elseif ($hashValidated == "INVALID HASH" and $txnResponseCode == "0") {
        // Tam giu
        $nv_transaction_status = 2;
    } else {
        // Giao dich that bai
        $nv_transaction_status = 3;
    }

    // Chuẩn hóa dữ liệu trả về để xử lý
    $responseData['orderid'] = intval(str_replace('GD', '', str_replace('WP', '', $orderInfo)));
    $responseData['transaction_id'] = $payment_id;
    $responseData['transaction_status'] = $nv_transaction_status;
    $responseData['transaction_time'] = NV_CURRENTTIME;

    $responseData['transaction_data'] = array('vpc_MerchTxnRef' => $vpc_MerchTxnRef);
    $responseData['transaction_data'] = nv_base64_encode(serialize($responseData['transaction_data']));
}
