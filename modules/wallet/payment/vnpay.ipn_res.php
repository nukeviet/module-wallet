<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET')) {
    die('Stop!!!');
}

// Căn cứ vào biến $walletReturnCode để trả kết quả cho cổng thanh toán

$returnData = [];

if ($walletReturnCode == 0) {
    $returnData['RspCode'] = '01';
    $returnData['Message'] = 'Order not found';
    nv_jsonOutput($returnData);
} elseif ($walletReturnCode == 1) {
    $returnData['RspCode'] = '02';
    $returnData['Message'] = 'Order already confirmed';
    nv_jsonOutput($returnData);
} elseif ($walletReturnCode == 4) {
    $returnData['RspCode'] = '00';
    $returnData['Message'] = 'Confirm Success';
    nv_jsonOutput($returnData);
} elseif ($walletReturnCode == 5) {
    $returnData['RspCode'] = '04';
    $returnData['Message'] = 'Invalid amount';
    nv_jsonOutput($returnData);
}

// Mã khác
$returnData['RspCode'] = '99';
$returnData['Message'] = $error;
nv_jsonOutput($returnData);
