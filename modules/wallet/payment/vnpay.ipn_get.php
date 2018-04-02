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

$returnData = array();

// Kiểm tra IP
if (!empty($payment_config['vnp_IPIPN'])) {
    $ipAllowed = false;
    $ipList = array_map('trim', explode(',', $payment_config['vnp_IPIPN']));
    foreach ($ipList as $ip) {
        if ($ip == $client_info['ip']) {
            $ipAllowed = true;
        }
    }
    if (!$ipAllowed) {
        $returnData['RspCode'] = '03';
        $returnData['Message'] = 'Wrong IP';
        nv_jsonOutput($returnData);
    }
}

// Đối với VNPay chỉ cần gọi lại file complete để xác định
require NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.complete.php';

// Lỗi không xác định
if (!empty($error)) {
    $returnData['RspCode'] = '99';
    $returnData['Message'] = $error;
    nv_jsonOutput($returnData);
}

// Sai checksum
if ($responseData['transaction_status'] == 5) {
    $returnData['RspCode'] = '97';
    $returnData['Message'] = 'Wrong checksum';
    nv_jsonOutput($returnData);
}

// Sau khi đã kiểm tra cơ bản hoàn trả lại để module xử lý
