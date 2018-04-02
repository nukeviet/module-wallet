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

$customer_id = $nv_Request->get_int('userid', 'get', 0);

$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE userid =" . $customer_id;
$result = $db->query($sql);
if ($result->rowCount() != 1) {
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=transaction");
    die();
}
$row = $result->fetch();

$row['full_name'] = trim($row['first_name'] . ' ' . $row['last_name']);

$page_title = $lang_module['transaction'] . ": " . $row['username'] . ($row['full_name'] ? " (" . $row['full_name'] . ")" : '');

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$page = $nv_Request->get_int('page', 'get', 0);
$per_page = 30;
$search_for = $namesearch = $transaction = '';
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=transaction";
//get info user
$arr_username = array("");

$sql = "SELECT userid, username FROM " . NV_USERS_GLOBALTABLE . "";

$list_id_user = "";
$query = $db->query($sql);
while ($row = $query->fetch()) {
    $arr_username[$row['userid']] = $row['username'];
    $list_id_user .= $row['userid'] . ",";
}

$sql = "SELECT SQL_CALC_FOUND_ROWS id,created_time,status,money_unit,money_total,money_net,userid FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction";
$sql .= " WHERE customer_id=" . $customer_id;
$sql .= " ORDER BY created_time DESC LIMIT " . $page . "," . $per_page . "";

$result = $db->query($sql);
$result_page = $db->query("SELECT FOUND_ROWS()");
$numf = $result_page->fetchColumn();
$all_page = ($numf) ? $numf : 1;

$arr_list_transaction = array();
while (list($id, $created_time, $status, $money_unit, $money_total, $money_net, $userid) = $result->fetch(3)) {
    $link_all = "<a href=\"javascript:void(0);\" onclick=\"nv_view_transaction_all('" . $customer_id . "');\"> ( " . $lang_module['viewallcustomer'] . " )</a>";
    $arr_list_transaction[$id] = array(
        'id' => $id, //
        'created_time' => date("d/m/Y H:i", $created_time), //
        'status' => ($status == 1) ? $lang_module['transaction1'] : $lang_module['transaction2'], //
        'money_unit' => $money_unit, //
        'money_total' => number_format($money_total, 0, '.', ' '), //
        'money_net' => number_format($money_net, 0, '.', ' '), //
        'userid' => ($arr_username[$userid]) ? $arr_username[$userid] : "", //
        'customer_id' => $customer_id);
}

$i = $page;
foreach ($arr_list_transaction as $element) {
    $i++;
    $class = ($i % 2) ? "class=\"second\"" : "";
    $xtpl->assign('class', $class);
    $xtpl->assign('stt', $i);
    $xtpl->assign('CONTENT', $element);
    $xtpl->parse('main.loop');
}

$xtpl->assign('val_namesearch', $namesearch);

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if ($generate_page) {

    $xtpl->assign('PAGE', $generate_page);
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

?>