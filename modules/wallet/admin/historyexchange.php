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

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$page = $nv_Request->get_int('page', 'get', 0);
$per_page = 30;
$dateview_search = $where = $dateview_search = "";
$dateview = $nv_Request->get_string('starttime', 'post,get');
if ($dateview != 0) {
    $hour = date("H", NV_CURRENTTIME);
    $minute = date("i", NV_CURRENTTIME);
    unset($m);
    preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $dateview, $m);
    $dateview_search = mktime($hour, $minute, 00, $m[2], $m[1], $m[3]);
}

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=historyexchange";
if ($dateview_search != "") {
    $where = " WHERE time_begin < " . $dateview_search . " AND time_end > " . $dateview_search . "";
}

$sql = "SELECT SQL_CALC_FOUND_ROWS log_id, money_unit, than_unit, exchange, time_begin, time_end FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange_log";
$order = " ORDER BY time_begin DESC LIMIT " . $page . "," . $per_page . "";

$sql .= $where . $order;

$result = $db->query($sql);
$result_page = $db->query("SELECT FOUND_ROWS()");
$numf = $result_page->fetchColumn();
$all_page = ($numf) ? $numf : 1;

$arr_list_transaction = array();
while (list($log_id, $money_unit, $than_unit, $exchange, $time_begin, $time_end) = $result->fetch(3)) {
    if ($exchange == intval($exchange)) {
        $exchange = number_format($exchange, 0, '.', ' ');
    }

    $arr_list_transaction[$log_id] = array(
        'log_id' => $log_id, //
        'money_unit' => $money_unit, //
        'than_unit' => $than_unit, //
        'exchange' => $exchange, //
        'time_begin' => date("d/m/Y H:i", $time_begin), //
        'time_end' => date("d/m/Y H:i", $time_end));

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

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if ($generate_page) {

    $xtpl->assign('PAGE', $generate_page);
}
if ($dateview != "") {
    $xtpl->assign('curenttime', $dateview);
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['transaction'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

?>