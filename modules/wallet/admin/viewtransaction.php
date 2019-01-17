<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);
$set_active_op = 'transaction';

$sql = "SELECT tb1.*, tb2.username admin_transaction, tb3.username accounttran, tb4.username customer_transaction
FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction tb1
LEFT JOIN " . NV_USERS_GLOBALTABLE . " tb2 ON tb1.adminid=tb2.userid
LEFT JOIN " . NV_USERS_GLOBALTABLE . " tb3 ON tb1.userid=tb3.userid
LEFT JOIN " . NV_USERS_GLOBALTABLE . " tb4 ON tb1.customer_id=tb4.userid
WHERE tb1.id = " . $id;
$result = $db->query($sql);
if ($result->rowCount() != 1) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
}
$row = $result->fetch();

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

if (empty($row['order_id'])) {
    $row['code'] = vsprintf('GD%010s', $row['id']);
} else {
    $row['code'] = vsprintf('WP%010s', $row['id']);
}
$row['created_time'] = date("H:i d/m/Y", $row['created_time']);
$row['transaction_time'] = date("H:i d/m/Y", $row['transaction_time']);
$row['status'] = ($row['status'] == 1) ? $lang_module['transaction1'] : $lang_module['transaction2'];
$row['money_total'] = get_display_money($row['money_total']);
$row['money_net'] = get_display_money($row['money_net']);
$row['money_discount'] = get_display_money($row['money_discount']);
$row['money_revenue'] = get_display_money($row['money_revenue']);
$row['transaction_status'] = isset($global_array_transaction_status[$row['transaction_status']]) ? $global_array_transaction_status[$row['transaction_status']] : 'N/A';
$row['transaction_type'] = isset($global_array_transaction_type[$row['transaction_type']]) ? $global_array_transaction_type[$row['transaction_type']] : 'N/A';
$row['accounttran'] = empty($row['accounttran']) ? 'N/A' : $row['accounttran'];
$row['transaction_uname'] = ($row['admin_transaction'] ? '<strong>' . $row['admin_transaction'] . '</strong>' : ($row['customer_transaction'] ? $row['customer_transaction'] : $row['customer_name']));
$row['payment'] = isset($global_array_payments[$row['payment']]) ? $global_array_payments[$row['payment']]['payment'] : $lang_module['transaction_payment_no'];
$row['paymentname'] = isset($global_array_payments[$row['payment']]) ? $global_array_payments[$row['payment']]['paymentname'] : $lang_module['transaction_payment_no'];

$row['transaction_id'] = $row['transaction_id'] ? $row['transaction_id'] : '--';
$row['customer_name'] = $row['customer_name'] ? $row['customer_name'] : '--';
$row['customer_email'] = $row['customer_email'] ? $row['customer_email'] : '--';
$row['customer_phone'] = $row['customer_phone'] ? $row['customer_phone'] : '--';
$row['customer_address'] = $row['customer_address'] ? $row['customer_address'] : '--';
$row['customer_info'] = $row['customer_info'] ? $row['customer_info'] : '--';
$row['transaction_info'] = $row['transaction_info'] ? $row['transaction_info'] : '--';

$xtpl->assign('CONTENT', $row);

$array_files = [];
$array_files_key = 0;

if (!empty($row['transaction_data'])) {
    $transaction_data = unserialize($row['transaction_data']);
    $transaction_data_size = 0;
    foreach ($transaction_data as $key => $value) {
        if (!empty($value)) {
            $transaction_data_size++;
            $xtpl->assign('OTHER_KEY', isset($lang_module[$key]) ? $lang_module[$key] : $key);

            if ($key == 'atm_filedepute' or $key == 'atm_filebill') {
                $files = explode('|', $value);
                if (isset($files[1])) {
                    $array_files_key++;
                    $array_files[$array_files_key] = [
                        'filename' => $files[0],
                        'filepath' => $files[1]
                    ];
                    $xtpl->assign('OTHER_VAL', $files[0]);
                    $xtpl->assign('OTHER_LINK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id . '&amp;file=' . $array_files_key);
                    $xtpl->parse('main.transaction_data.loop.link');
                }
            } else {
                $xtpl->assign('OTHER_VAL', $value);
                $xtpl->parse('main.transaction_data.loop.text');
            }

            $xtpl->parse('main.transaction_data.loop');
        }
    }
    if ($transaction_data_size > 0) {
        $xtpl->parse('main.transaction_data');
    }
}

// Tải file về: Đường dẫn file này bí mật
$file_key = $nv_Request->get_int('file', 'get', '');
if (isset($array_files[$file_key])) {
    $file_src = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $array_files[$file_key]['filepath'];
    $download = new NukeViet\Files\Download($file_src, NV_UPLOADS_REAL_DIR . '/' . $module_upload, $array_files[$file_key]['filename'], true);
    $download->download_file();
    die();
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['detailtransaction'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
