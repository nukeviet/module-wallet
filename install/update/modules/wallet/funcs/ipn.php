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

/*
 * Ghi log request
 */
try {
    $array_insert = [
        'userid' => defined('NV_IS_USER') ? $user_info['userid'] : 0,
        'log_ip' => NV_CLIENT_IP,
        'log_data' => [],
        'request_method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '',
        'user_agent' => NV_USER_AGENT
    ];
    if (!empty($_GET)) {
        $array_insert['log_data']['get'] = $_GET;
    }
    if (!empty($_POST)) {
        $array_insert['log_data']['post'] = $_POST;
    }
    $array_insert['log_data'] = json_encode($array_insert['log_data']);
    $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_ipn_logs (
        userid, log_ip, log_data, request_method, request_time, user_agent
    ) VALUES (
        :userid, :log_ip, :log_data, :request_method, " . NV_CURRENTTIME . ", :user_agent
    )";
    $sth = $db->prepare($sql);
    $sth->bindParam(':userid', $array_insert['userid'], PDO::PARAM_INT);
    $sth->bindParam(':log_ip', $array_insert['log_ip'], PDO::PARAM_STR);
    $sth->bindParam(':log_data', $array_insert['log_data'], PDO::PARAM_STR, strlen($array_insert['log_data']));
    $sth->bindParam(':request_method', $array_insert['request_method'], PDO::PARAM_STR);
    $sth->bindParam(':user_agent', $array_insert['user_agent'], PDO::PARAM_STR, strlen($array_insert['user_agent']));
    $sth->execute();
    unset($array_insert, $sth);
} catch (Exception $exception) {
    trigger_error(print_r($exception, true));
}

$payment = $nv_Request->get_title('payment', 'get', '');
if (!isset($global_array_payments[$payment]) or !file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.ipn_get.php')) {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
}

// Các biến dùng cho cổng thanh toán
$row_payment = $global_array_payments[$payment];
$payment_config = unserialize(nv_base64_decode($row_payment['config']));
$payment_config['paymentname'] = $row_payment['paymentname'];
$payment_config['domain'] = $row_payment['domain'];

// Nếu có lỗi thì đặt vào biến này
$error = '';

// Dữ liệu trả về đặt vào biến này
$responseData = [
    'ordertype' => '', // Kiểu giao dịch: pay là thanh toán các đơn hàng khác, recharge là nạp tiền vào ví
    'orderid' => '', // Kiểu text, ID của giao dịch được lưu trước vào CSDL dùng để cập nhật thanh toán
    'transaction_id' => '', // Kiểu text, ID giao dịch trên cổng thanh toán
    'transaction_status' => 0, // Kiểu số, trạng thái giao dịch quy chuẩn
    'transaction_time' => 0, // Kiểu số, thời gian giao dịch
    'transaction_data' => '' // Kiểu text, có thể là serialize array
];

// Gọi file xử lý dữ liệu trả về
require NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".ipn_get.php";

// Thông tin trả về để cổng thanh toán xử lý tiếp (Xuất thông tin cho bên cổng thanh toán)
/**
 * Quy chuẩn:
 * 99 -> Lỗi không xác định
 * 0 => Không tìm thấy giao dịch trong CSDL
 * 1 => Giao dịch đã được xử lý trước đó
 * 2 => Không thể cập nhật trạng thái giao dịch
 * 4 => Cập nhật trạng thái giao dịch thành công
 * 5 => Số tiền Không hợp lệ
 */
$walletReturnCode = 99;

// Kiểm tra đơn hàng
if ($responseData['ordertype'] == 'pay') {
    // Lấy giao dịch đã lưu vào CSDL trước đó
    $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE id = :id");
    $stmt->bindParam(':id', $responseData['orderid'], PDO::PARAM_STR);
    $stmt->execute();
    $transaction = $stmt->fetch();
    if (empty($transaction)) {
        // Không tìm thấy giao dịch
        $walletReturnCode = 0;
    } else {
        // Các đơn hàng
        $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE id = :id");
        $stmt->bindParam(':id', $transaction['order_id'], PDO::PARAM_STR);
        $stmt->execute();
        $order_info = $stmt->fetch();
        if (empty($order_info)) {
            // Không tìm thấy giao dịch
            $walletReturnCode = 0;
        } else {
            // Giao dịch đã được xử lý
            if ($order_info['paid_status'] != 0 or $transaction['transaction_status'] != 0) {
                // Giao dịch đã được xử lý
                $walletReturnCode = 1;
            } elseif (floatval($order_info['money_amount']) != $responseData['amount']) {
                // Số tiền không hợp lệ
                $walletReturnCode = 5;

                // Cập nhật trạng thái thất bại
                $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                    transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = 6,
                    transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
                WHERE id = ' . $transaction['id'];
                $db->exec($sql);
            } else {
                // Cập nhật lại giao dịch
                $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                    transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = ' . $responseData['transaction_status'] . ',
                    transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
                WHERE id = ' . $transaction['id'];
                if (!$db->exec($sql)) {
                    $walletReturnCode = 2;
                } else {
                    $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET
                        paid_status=" . $responseData['transaction_status'] . ",
                        paid_id=" . $db->quote(sprintf('GD%010s', $transaction['id'])) . ",
                        paid_time=" . $responseData['transaction_time'] . "
                    WHERE id=" . $order_info['id']);
                    if (!$check) {
                        $walletReturnCode = 2;
                    } else {
                        $nv_Cache->delMod($module_name);
                        $walletReturnCode = 4;
                    }
                }
            }
        }
    }
} else {
    // Nạp tiền vào tài khoản
    $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE id = :id");
    $stmt->bindParam(':id', $responseData['orderid'], PDO::PARAM_STR);
    $stmt->execute();
    $order_info = $stmt->fetch();
    if (empty($order_info)) {
        // Không tìm thấy giao dịch
        $walletReturnCode = 0;
    } else {
        // Giao dịch đã được xử lý
        if ($order_info['transaction_status'] != 0) {
            // Giao dịch đã được xử lý
            $walletReturnCode = 1;
        } elseif (floatval($order_info['money_net']) != $responseData['amount']) {
            // Số tiền không hợp lệ
            $walletReturnCode = 5;

            // Cập nhật lại giao dịch thất bại
            $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = 6,
                transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
            WHERE id = ' . $order_info['id'];
            $db->exec($sql);
        } else {
            $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = ' . $responseData['transaction_status'] . ',
                transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
            WHERE id = ' . $order_info['id'];

            if (!$db->exec($sql)) {
                $walletReturnCode = 2;
            } else {
                $walletReturnCode = 4;

                // Cập nhật số tiền vào tài khoản tại đây
                if ($responseData['transaction_status'] == 4) {
                    $check = nv_wallet_money_in($order_info['userid'], $order_info['money_unit'], $order_info['money_total']);
                    if (!$check) {
                        $walletReturnCode = 2;
                    }
                }

                $nv_Cache->delMod($module_name);
            }
        }
    }
}

// Gọi file trả kết quả cho cổng thanh toán
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.ipn_res.php')) {
    require NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".ipn_res.php";
}

nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
