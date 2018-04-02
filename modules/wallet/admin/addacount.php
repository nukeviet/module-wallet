<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

// Kiểm tra số tiền
$money = $nv_Request->get_title('money', 'post', 0);
$money = floatval(str_replace(',', '', $money));
if ($money <= 0) {
    nv_htmlOutput($lang_module['addacc_error_money']);
}

// Kiểm tra thành viên
$userid = $nv_Request->get_int('userid', 'post', 0);
if (empty($userid)) {
    nv_htmlOutput($lang_module['addacc_error_user']);
}
$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE userid =" . $userid;
$result = $db->query($sql);
$customer_info = $result->fetch();
if (empty($customer_info)) {
    nv_htmlOutput($lang_module['addacc_error_userexists']);
}

$typemoney = $nv_Request->get_title('typemoney', 'post', 'VND');
if (!isset($global_array_money_sys[$typemoney])) {
    nv_htmlOutput($lang_module['addacc_error_typymoney']);
}

$notice = $nv_Request->get_title('notice', 'post', '');

$typeadd = $nv_Request->get_title('typeadd', 'post', '+');
$typeadd = ($typeadd == '-') ? $typeadd : "+";
$loaigiaodich = ($typeadd == '-') ? -1 : 1;
$transaction_status = 4;

$transaction_type = $nv_Request->get_int('trantype', 'post', -1);
if (!isset($global_array_transaction_type[$transaction_type])) {
    $transaction_type = -1;
}

$contents = "NOT";

// Cập nhật giao dịch
// Giao dịch do admin khởi tạo thì không có tính phí vào mà cộng trực tiếp vào tiền luôn
$sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (
    created_time, status, money_unit, money_total, money_net, money_revenue, userid, adminid,
    customer_info, transaction_type, transaction_status, transaction_time, transaction_info, transaction_data, payment )
VALUES (
    " . NV_CURRENTTIME . "," . $loaigiaodich . "," . $db->quote($typemoney) . ",
    " . $money . ", " . $money . ", " . $money . ", " . $userid . ", " . $admin_info['admin_id'] . ", '', " . $transaction_type . ",
    " . $transaction_status . "," . NV_CURRENTTIME . ", " . $db->quote($notice) . ", '', ''
);";

if ($db->insert_id($sql)) {
    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money WHERE userid ='" . $userid . "' AND money_unit=" . $db->quote($typemoney);
    $result = $db->query($sql);

    if ($result->rowCount()) {
        $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_money SET
            money_in= money_in" . $typeadd . $money . ",
            money_total = money_total" . $typeadd . $money . "
        WHERE userid= " . $userid . " AND money_unit=" . $db->quote($typemoney);
        $res = $db->exec($sql);
    } else {
        $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money (
            userid, created_time, created_userid, status, money_unit, money_in, money_out, money_total, note, tokenkey
        ) VALUES(
            " . ($userid) . ", " . NV_CURRENTTIME . ", " . $admin_info['userid'] . ", 1, " . $db->quote($typemoney) . ", " . $money . ", 0, " . $money . ", '', ''
        )";
        $res = $db->exec($sql);
    }

    if ($res) {
        $contents = "OK";
    } else {
        $contents = $lang_module['addacc_error_update_money'];
    }
} else {
    $contents = $lang_module['addacc_error_save_transiton'];
}

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo ($contents);
include NV_ROOTDIR . '/includes/footer.php';
