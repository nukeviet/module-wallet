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

$page_title = $lang_module['transaction1'];
$nv_redirect = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=money");
$nv_redirect_his = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=historyexchange");

$payment = $nv_Request->get_title('payment', 'get', '');

// Kiểm tra cổng tồn tại
if (!isset($global_array_payments[$payment])) {
    redict_link($lang_module['pay_error_payport'], $lang_module['cart_back'], $nv_redirect);
}

// Kiểm tra file xử lý của cổng thanh toán
if (!file_exists(NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".complete.php")) {
    redict_link($lang_module['pay_error_completeport'], $lang_module['cart_back'], $nv_redirect);
}

// Biến này đặt cùng tên với phần recharge để dễ quản lý
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
require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".complete.php";

// Nếu có lỗi thì thông báo lỗi
if (!empty($error)) {
    redict_link($error, $lang_module['cart_back'], $nv_redirect);
}

// Hủy bỏ giao dịch
if ($responseData['transaction_status'] < 0) {
    redict_link($lang_module['pay_user_cancel'], $lang_module['cart_back'], $nv_redirect);
}

// Lấy giao dịch đã lưu vào CSDL trước đó
$stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE id = :id");
$stmt->bindParam(':id', $responseData['orderid'], PDO::PARAM_STR);
$stmt->execute();
$order_info = $stmt->fetch();
if (empty($order_info)) {
    redict_link($lang_module['transition_no_exists'], $lang_module['cart_back'], $nv_redirect);
}

/**
 * Đối với cổng thanh toán vnpay
 * Chỉ thông báo trạng thái giao dịch và không làm gì
 * Việc cập nhật do IPN đảm nhiệm
 */
if ($payment == 'vnpay') {
    // Thông báo thành công
    // Giao dịch hoàn toàn thành công, IPN đã cập nhật trước
    if ($responseData['transaction_status'] == 4 and $order_info['transaction_status'] == 4) {
        redict_link($lang_module['pay_save_ok_title'], $lang_module['pay_save_ok_body'], $nv_redirect);
    }
    // Giao dịch thành công, chờ IPN
    if ($responseData['transaction_status'] == 4) {
        redict_link($lang_module['pay_save_ok_wait'], $lang_module['cart_back'], $nv_redirect);
    }
    // Sai checksum
    if ($responseData['transaction_status'] == 5) {
        redict_link($lang_module['pay_error_checkhash'], $lang_module['cart_back'], $nv_redirect);
    }
    // Các trạng thái khác
    $transaction_status = isset($global_array_transaction_status[$responseData['transaction_status']]) ? $global_array_transaction_status[$responseData['transaction_status']] : 'N/A';
    redict_link($lang_module['pay_info_response'] . ' ' . $transaction_status, $lang_module['cart_back'], $nv_redirect);
}

// Giao dịch đã được xử lý
if ($order_info['transaction_status'] != 0) {
    redict_link($lang_module['pay_error_tranisprocessed'], $lang_module['cart_back'], $nv_redirect);
}

$sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
    transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = ' . $responseData['transaction_status'] . ',
    transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
WHERE id = ' . $order_info['id'];

if (!$db->exec($sql)) {
    redict_link($lang_module['payclass_error_save_transaction'], $lang_module['cart_back'], $nv_redirect);
}

// Nếu thanh toán hoàn tất thì cập nhật tài khoản và thông báo
if ($responseData['transaction_status'] == 4) {
    $check = nv_wallet_money_in($order_info['userid'], $order_info['money_unit'], $order_info['money_total']);
    if (!$check) {
        redict_link($lang_module['pay_error_update_account'], $lang_module['cart_back'], $nv_redirect);
    }
    redict_link($lang_module['pay_save_ok_title'], $lang_module['pay_save_ok_body'], $nv_redirect);
}

// Thông báo trạng thái hiện tại
if ($responseData['transaction_status'] != 4) {
    // Giao dịch chưa thành công thì chuyển về trang lịch sử thanh toán để xem
    $nv_redirect = $nv_redirect_his;
}
if ($payment == 'manual' or $payment == 'ATM') {
    $message = $payment_config['completemessage'];
} else {
    $transaction_status = isset($global_array_transaction_status[$responseData['transaction_status']]) ? $global_array_transaction_status[$responseData['transaction_status']] : 'N/A';
    $message = $lang_module['pay_info_response'] . ' ' . $transaction_status;
}
redict_link($message, $lang_module['cart_back'], $nv_redirect);
