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

$request_data = [];
$request_data['code'] = $nv_Request->get_title('code', 'get', '');
$request_data['money'] = $nv_Request->get_title('money', 'get', '');
$request_data['unit'] = $nv_Request->get_title('unit', 'get', '');
$request_data['info'] = $nv_Request->get_string('info', 'get', '');
$request_data['checksum'] = $nv_Request->get_title('checksum', 'get', '');

// Loại giao dịch
$responseData['ordertype'] = (preg_match('/^GD/', $request_data['code']) ? 'recharge' : 'pay');

// ID giao dịch nếu nạp tiền hoặc là ID đơn hàng nếu thanh toán cho các module khác
$responseData['orderid'] = intval(str_replace('GD', '', str_replace('WP', '', $request_data['code'])));

// Thời điểm giao dịch
$responseData['transaction_time'] = NV_CURRENTTIME;

if ($responseData['orderid'] < 0 or $responseData['orderid'] > 9999999999) {
    $error = $lang_module['transition_no_exists'];
} else {
    /*
     * Xác định giao dịch đã lưu trước trong CSDL
     * Thao tác này chỉ lấy với cổng manual, vì sau khi lấy thì trong complete.php lại kiểm tra lần nữa
     */
    $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE id = :id");
    $stmt->bindParam(':id', $responseData['orderid'], PDO::PARAM_STR);
    $stmt->execute();
    $__order_info = $stmt->fetch();

    if (empty($__order_info)) {
        $error = $lang_module['transition_no_exists'];
    } else {
        // Lấy lại transaction_data từ CSDL
        $responseData['transaction_data'] = $__order_info['transaction_data'];

        // Tính lại checksum để đối chiếu
        $checksum_str = $request_data['code'] . $request_data['money'] . $request_data['unit'] . $request_data['info'] . $__order_info['tokenkey'];
        $checksum = hash('sha256', $checksum_str);

        if ($checksum === $request_data['checksum']) {
            // Mặc định cổng thanh toán VietQR luôn cho về đang chờ xử lý
            $responseData['transaction_status'] = 1;
        } else {
            $responseData['transaction_status'] = 5;
        }
    }
}
