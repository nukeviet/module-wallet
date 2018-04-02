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

$vnp_SecureHash = isset($_GET['vnp_SecureHash']) ? $_GET['vnp_SecureHash'] : '';
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}
unset($inputData['vnp_SecureHashType']);
unset($inputData['vnp_SecureHash']);
unset($inputData[NV_LANG_VARIABLE]);
unset($inputData[NV_NAME_VARIABLE]);
unset($inputData[NV_OP_VARIABLE]);
unset($inputData['wpay']);
unset($inputData['wchecksum']);
unset($inputData['payment']);
unset($inputData['wpayportres']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . $key . "=" . $value;
    } else {
        $hashData = $hashData . $key . "=" . $value;
        $i = 1;
    }
}
$secureHash = md5($payment_config['vnp_HashSecret'] . $hashData);

$vnp_TxnRef = (isset($_GET['vnp_TxnRef']) ? $_GET['vnp_TxnRef'] : '');

// Loại giao dịch
$responseData['ordertype'] = (preg_match('/^GD/', $vnp_TxnRef) ? 'recharge' : 'pay');

// ID giao dịch nếu nạp tiền hoặc là ID đơn hàng nếu thanh toán cho các module khác
$responseData['orderid'] = intval(str_replace('GD', '', str_replace('WP', '', $vnp_TxnRef)));

// Mã giao dịch trên cổng thanh toán
$responseData['transaction_id'] = isset($_GET['vnp_TransactionNo']) ? $_GET['vnp_TransactionNo'] : '';

// Thời gian giao dịch trên hệ thống
$vnp_PayDate = isset($_GET['vnp_PayDate']) ? $_GET['vnp_PayDate'] : '';
if (preg_match('/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$/', $vnp_PayDate, $m)) {
    $responseData['transaction_time'] = strtotime($m[1] . '-' . $m[2] . '-' . $m[3] . 'T' . $m[4] . ':' . $m[5] . ':' . $m[6] . '+07:00');
}

// Lưu lại một số thông tin giao dịch khác
$transaction_data = array(
    'code' => (isset($_GET['vnp_ResponseCode']) ? $_GET['vnp_ResponseCode'] : ''),
    'bankcode' => (isset($_GET['vnp_BankCode']) ? $_GET['vnp_BankCode'] : ''),
    'bankcodeTranNo' => (isset($_GET['vnp_BankTranNo']) ? $_GET['vnp_BankTranNo'] : ''),
    'bankcodeTranNo' => (isset($_GET['vnp_BankTranNo']) ? $_GET['vnp_BankTranNo'] : '')
);
$responseData['transaction_data'] = serialize($transaction_data);

if ($secureHash == $vnp_SecureHash) {
    $vnp_ResponseCode = isset($_GET['vnp_ResponseCode']) ? $_GET['vnp_ResponseCode'] : '';
    if ($vnp_ResponseCode === '00') {
        // Thành công
        $responseData['transaction_status'] = 4;
    } else {
        $responseData['transaction_status'] = 3;
    }
} else {
    // Sai HASH
    $responseData['transaction_status'] = 5;
}
