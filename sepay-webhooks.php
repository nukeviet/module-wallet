<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/*
 * https://domain.com/sepay-webhooks.php
 */
define('NV_MAINFILE', true);
define('NV_SYSTEM', true);
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/config.php';

$site_timezone = 'Asia/Ho_Chi_Minh';
date_default_timezone_set($site_timezone);

define('NV_START_TIME', microtime(true));
define('NV_CURRENTTIME', time());

$_time_zone_db = preg_replace('/^([\+|\-]{1}\d{2})(\d{2})$/', '$1:$2', date('O'));
$driver_options = [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_PERSISTENT => $db_config['persistent'],
    PDO::ATTR_CASE => PDO::CASE_LOWER,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

$returnData = [];

// Bắt buộc cho Sepay biết nếu là thành công
$returnData['success'] = false;
$returnData['message'] = 'No information!';

$dsn = $db_config['dbtype'] . ':dbname=' . $db_config['dbname'] . ';host=' . $db_config['dbhost'] . ';charset=' . $db_config['charset'];
if (!empty($db_config['dbport'])) {
    $dsn .= ';port=' . $db_config['dbport'];
}
$driver_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
try {
    $db = new PDO($dsn, $db_config['dbuname'], $db_config['dbpass'], $driver_options);
    $db->exec("SET SESSION time_zone='" . $_time_zone_db . "'");
} catch (PDOException $e) {
    $returnData['message'] = $e->getMessage();
    jsonOut($returnData, 500);
}

$module_data = 'wallet';

// Lấy cổng thanh toán sepay

$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE payment='sepay'";
$row_payment = $db->query($sql)->fetch();
if (empty($row_payment)) {
    $returnData['message'] = 'SePay config not found!!!';
    jsonOut($returnData, 404);
}
$payment_config = empty($row_payment['config']) ? [] : unserialize(base64_decode(strtr($row_payment['config'], '-_,', '+/='), true));
if (empty($payment_config) or !is_array($payment_config)) {
    $returnData['message'] = 'SePay config wrong!!!';
    jsonOut($returnData, 404);
}

$payment_config['account_no'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['account_no']));
$payment_config['account_name'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['account_name']));
$payment_config['acq_id'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['acq_id']));
$payment_config['bank_branch'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(';', $payment_config['bank_branch']));

if (empty($payment_config['account_no'])) {
    $returnData['message'] = 'No account allowed!!!';
    jsonOut($returnData, 404);
}

// Kiểm tra key xác thực đúng
$http_authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$apikey_receive = '';
if (preg_match('/^Apikey[\s]+([0-9a-zA-Z]{32})$/', $http_authorization, $m)) {
    $apikey_receive = $m[1];
}
if (empty($apikey_receive)) {
    $returnData['message'] = 'No APIKey found!';
    jsonOut($returnData, 401);
}
if ($apikey_receive !== $payment_config['apikey']) {
    $returnData['message'] = 'Wrong APIKey!';
    jsonOut($returnData, 401);
}

// Xác định IP máy chủ sepay
$client_ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_VIA'] ?? $_SERVER['HTTP_X_COMING_FROM'] ?? $_SERVER['HTTP_COMING_FROM'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_X_FORWARDED'] ?? $_SERVER['HTTP_FORWARDED_FOR'] ?? $_SERVER['HTTP_FORWARDED'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
$allowed_ips = empty($payment_config['sepay_ips']) ? [] : array_filter(array_unique(array_map('trim', explode(',', $payment_config['sepay_ips']))));
if (!empty($allowed_ips) and !in_array($client_ip, $allowed_ips, true)) {
    $returnData['message'] = 'IP not allowed!';
    jsonOut($returnData, 403);
}

$array = [];
$array['id'] = intval($_POST['id'] ?? 0);
$array['gateway'] = htmlspecialchars($_POST['gateway'] ?? '');
$array['transactionDate'] = intval(strtotime($_POST['transactionDate'] ?? ''));
$array['accountNumber'] = htmlspecialchars($_POST['accountNumber'] ?? '');
$array['transferType'] = $_POST['transferType'] ?? '';
$array['transferAmount'] = round(floatval($_POST['transferAmount'] ?? 0));
$array['accumulated'] = round(floatval($_POST['accumulated'] ?? 0));
$array['transferContent'] = trim(htmlspecialchars($_POST['content'] ?? ''));
$array['subAccount'] = trim(htmlspecialchars($_POST['subAccount'] ?? ''));
$array['referenceCode'] = trim(htmlspecialchars($_POST['referenceCode'] ?? ''));
$array['description'] = trim(htmlspecialchars($_POST['description'] ?? ''));

if (preg_match('/(GD|WP)([0-9]{10})/', $array['transferContent'], $m)) {
    $transaction_type = $m[1] == 'WP' ? 'pay' : 'recharge';
    $transaction_id = intval($m[2]);
    $array['status'] = 1;
} else {
    $transaction_type = '';
    $transaction_id = 0;
    $array['status'] = 0;
}

// Kiểm tra ID
if (empty($array['id'])) {
    $returnData['message'] = 'No id found!';
    jsonOut($returnData, 400);
}
// Kiểm tra số tài khoản nhận đúng
if (empty($array['accountNumber'])) {
    $returnData['message'] = 'No accountNumber found!';
    jsonOut($returnData, 400);
}
if (!in_array($array['accountNumber'], $payment_config['account_no'])) {
    $returnData['message'] = 'Wrong accountNumber!';
    jsonOut($returnData, 400);
}

// Lấy kiểu, chỉ chấp nhận kiểu tiền vào
if (empty($array['transferType'])) {
    $returnData['message'] = 'No transferType found!';
    jsonOut($returnData, 400);
}
if ($array['transferType'] !== 'in') {
    $returnData['message'] = 'Wrong transferType!';
    jsonOut($returnData, 400);
}

// Lưu log giao dịch
try {
    $sql = "INSERT IGNORE INTO " . $db_config['prefix'] . "_" . $module_data . "_sepay_logs (
        id, gateway, banktime, addtime, content, transfer_amount, accumulated, sub_account, reference_code, description, status
    ) VALUES (
        " . $array['id'] . ",
        " . $db->quote($array['gateway']) . ",
        " . $array['transactionDate'] . ",
        " . NV_CURRENTTIME . ",
        " . $db->quote($array['transferContent']) . ",
        " . $array['transferAmount'] . ",
        " . $array['accumulated'] . ",
        " . $db->quote($array['subAccount']) . ",
        " . $db->quote($array['referenceCode']) . ",
        " . $db->quote($array['description']) . ",
        " . $array['status'] . "
    )";
    $db->query($sql);
} catch (Exception $e) {
    // Nothing
}

// Số tiền giao dịch
if (empty($array['transferAmount'])) {
    $returnData['message'] = 'No transferAmount found!';
    jsonOut($returnData, 400);
}
if ($array['transferAmount'] < 0) {
    $returnData['message'] = 'Wrong transferAmount!';
    jsonOut($returnData, 400);
}

// Message giao dịch
if (empty($array['transferContent'])) {
    $returnData['message'] = 'No content found!';
    jsonOut($returnData, 400);
}

if (empty($array['status'])) {
    $returnData['message'] = 'The transaction code is not recognized!';
    jsonOut($returnData, 400);
}

// Lấy giao dịch đã lưu vào CSDL trước đó
$stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE id = :id");
$stmt->bindParam(':id', $transaction_id, PDO::PARAM_INT);
$stmt->execute();
$transaction = $stmt->fetch();
if (empty($transaction)) {
    // Không tìm thấy giao dịch
    $returnData['message'] = 'Transaction not found!';
    jsonOut($returnData, 404);
}

// Kiểm tra trạng thái giao dịch phải bằng 0
if ($transaction['transaction_status'] != 0) {
    $returnData['message'] = 'This transaction has been processed!';
    jsonOut($returnData, 403);
}

// Kiểm tra số tiền giao dịch mà ít hơn số tiền trong hóa đơn
if (floatval($transaction['money_net']) > $array['transferAmount']) {
    $returnData['message'] = 'Transaction amount is not enough!';
    jsonOut($returnData, 403);
}

$referenceCode = mb_substr(htmlspecialchars($_POST['referenceCode'] ?? ''), 0, 200);
unset($_POST['accumulated'], $_POST['description']);
$transaction_data = serialize($_POST);

if ($transaction_type == 'pay') {
    /*
     * Thanh toán đơn hàng
     */

    // Tìm đơn hàng
    $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE id = :id");
    $stmt->bindParam(':id', $transaction['order_id'], PDO::PARAM_STR);
    $stmt->execute();
    $order_info = $stmt->fetch();

    if (empty($order_info)) {
        // Không tìm thấy đơn hàng
        http_response_code(404);
        $returnData['message'] = 'Order not found!';
        nv_jsonOutput($returnData);
    }

    // Giao dịch đã được xử lý
    if ($order_info['paid_status'] != 0) {
        // Đơn hàng đã được xử lý
        http_response_code(403);
        $returnData['message'] = 'This order has been processed!';
        nv_jsonOutput($returnData);
    }

    // Cập nhật trạng thái giao dịch
    $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
        transaction_id = ' . $db->quote($referenceCode) . ', transaction_status=4,
        transaction_time=' . NV_CURRENTTIME . ', transaction_data=' . $db->quote($transaction_data) . '
    WHERE id=' . $transaction_id;
    if (!$db->exec($sql)) {
        http_response_code(500);
        $returnData['message'] = 'Can not update transaction!';
        nv_jsonOutput($returnData);
    }

    // Cập nhật trạng thái đơn hàng
    $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET
        paid_status=4,
        paid_id=" . $db->quote(sprintf('WP%010s', $transaction['id'])) . ",
        paid_time=" . NV_CURRENTTIME . "
    WHERE id=" . $order_info['id']);
    if (!$check) {
        http_response_code(500);
        $returnData['message'] = 'Can not update order!';
        nv_jsonOutput($returnData);
    }

    // Cập nhật lại trạng thái xử lý
    $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_sepay_logs SET mapping_status=1 WHERE id=" . $array['id'];
    $db->query($sql);

    http_response_code(201);
    $returnData['success'] = true;
    $returnData['message'] = 'Success!';
    nv_jsonOutput($returnData);
}

// Nạp tiền vào ví
$sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
    transaction_id = ' . $db->quote($referenceCode) . ', transaction_status=4,
    transaction_time=' . NV_CURRENTTIME . ', transaction_data=' . $db->quote($transaction_data) . '
WHERE id=' . $transaction_id;
if (!$db->exec($sql)) {
    $returnData['message'] = 'Can not update transaction!';
    jsonOut($returnData, 500);
}

$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money WHERE
userid=" . $transaction['userid'] . " AND money_unit=" . $db->quote($transaction['money_unit']);
$result = $db->query($sql);

$check = false;
if ($result->rowCount()) {
    $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_money SET
        money_in= money_in+" . $transaction['money_total'] . ",
        money_total = money_total+" . $transaction['money_total'] . "
    WHERE userid= " . $transaction['userid'] . " AND money_unit=" . $db->quote($transaction['money_unit']);
    $check = $db->exec($sql);
} else {
    $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money (
        userid, created_time, created_userid, status, money_unit, money_in, money_out, money_total, note, tokenkey
    ) VALUES (
        " . $transaction['userid'] . ", " . NV_CURRENTTIME . ", 0, 1, " . $db->quote($transaction['money_unit']) . ",
        '" . $transaction['money_total'] . "', 0, '" . $transaction['money_total'] . "',
        " . $db->quote('System auto creat account') . ", ''
    )";
    $check = $db->exec($sql);
}
if (!$check) {
    $returnData['message'] = 'Can not update money!';
    jsonOut($returnData, 500);
}

// Cập nhật lại trạng thái xử lý
$sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_sepay_logs SET mapping_status=1 WHERE id=" . $array['id'];
$db->query($sql);

$returnData['success'] = true;
$returnData['message'] = 'Success!';
jsonOut($returnData, 201);

/**
 * @param array $json
 * @param int $code
 */
function jsonOut($json, $code = 200)
{
    http_response_code($code);
    $html_headers = [];
    $html_headers['X-Frame-Options'] = 'SAMEORIGIN';
    $html_headers['Content-Type'] = 'application/json';
    $html_headers['Last-Modified'] = gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT';
    $html_headers['Cache-Control'] = 'max-age=0, no-cache, no-store, must-revalidate'; // HTTP 1.1.
    $html_headers['Pragma'] = 'no-cache'; // HTTP 1.0.
    $html_headers['Expires'] = '-1'; // Proxies.
    $html_headers['X-Content-Type-Options'] = 'nosniff';
    $html_headers['X-XSS-Protection'] = '1; mode=block';
    ob_start('ob_gzhandler');
    echo json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit(0);
}
