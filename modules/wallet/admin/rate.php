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

$page_title = $lang_module['rate'];

$sql = "SELECT config_value  FROM " . NV_CONFIG_GLOBALTABLE . " where config_name = 'money_unit' AND lang ='" . NV_LANG_DATA . "' ";
$result = $db->query($sql);
$config_value = $result->fetchColumn();
$code = $config_value;
$data = "";

unset($m);
$datecurent = date("d.m.Y", NV_CURRENTTIME);
preg_match("/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $datecurent, $m);
$dateview = mktime(00, 00, 00, $m[2], $m[1], $m[3]);
$dateview1 = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
$id = $nv_Request->get_int('id', 'get', 0);
$dateview_i = date("d.m.Y", NV_CURRENTTIME);
//view rate
if ($nv_Request->get_string('code', 'get')) {
    $code = $nv_Request->get_string('code', 'get');
}
if ($nv_Request->get_string('getrate', 'post,get')) {
    $code = $nv_Request->get_string('code', 'post,get');
    $dateview = $nv_Request->get_string('starttime', 'post,get');
    $dateview_i = $dateview;
    unset($m);
    preg_match("/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $dateview, $m);
    $dateview = mktime(00, 00, 00, $m[2], $m[1], $m[3]);
    $dateview1 = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
}
//addnew rate
if ($nv_Request->get_string('savecat', 'post,get')) {
    $code = $nv_Request->get_string('codecurent', 'post,get');
    $arr_currency = $nv_Request->get_array('currency', 'get,post', 'string');
    $arr_money_code = $nv_Request->get_array('money_code', 'get,post', 'string');

    $id_i = $nv_Request->get_int('save_rate', 'post,get', 0);
    if ($id_i > 0) {
        $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rate SET rate = " . $arr_currency[0] . ", edittime = " . NV_CURRENTTIME . " WHERE id = " . $id_i . " ";
        $db->query($sql);
        $nv_Cache->delMod($module_name);
    } else {
        for ($i = 0; $i < count($arr_currency); $i++) {
            if ($arr_currency[$i] != "") {
                $query = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rate (id,currency, currency_change, rate,addtime, edittime) VALUES (NULL, '" . $arr_money_code[$i] . "', '" . $code . "', " . $arr_currency[$i] . "," . NV_CURRENTTIME . " , " . NV_CURRENTTIME . " )";
                $db->insert_id($query);
                $nv_Cache->delMod($module_name);
            }
        }
    }

} elseif (!empty($id)) {
    $data = $db->query("SELECT id, currency, currency_change, rate, edittime FROM " . $db_config['prefix'] . "_" . $module_data . "_rate WHERE id=" . $id)->fetch();
}

$xtpl = new XTemplate("rate.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);

$xtpl->assign('curenttime', $dateview_i);

//show for add new

$re = $db->query("SELECT id, code, currency FROM " . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . " ORDER BY id");
$arr_money = array();
while ($row = $re->fetch()) {
    if ($code == $row['code']) {
        $select = "selected=\"selected\"";
    } else {
        $select = "";
    }
    $xtpl->assign('selectted', $select);
    $link_change = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=rate&id=" . $row['code'];
    $xtpl->assign('link_change', $link_change);
    $arr_money[$row['code']] = $row['currency'];
    $xtpl->assign('DATAMONEY', $row);
    $xtpl->parse('main.data.money');
}

unset($arr_money[$code]);

//parse for edit rate
if ($data != "" && isset($arr_money[$data['currency']])) {
    if ($data['rate'] < 1) {
        $data['rate'] = number_format($data['rate'], 9, '.', ' ');
    }
    $xtpl->assign('currency', $data['rate']);
    $xtpl->assign('code', $data['currency_change']);
    $xtpl->assign('money', $data['currency']);
    $xtpl->assign('id_save', $data['id']);
    $xtpl->parse('main.loopmoney');
}
//parse for add rate
else {
    $query_rate = "SELECT currency_change, rate FROM " . $db_config['prefix'] . "_" . $module_data . "_rate WHERE currency_change !='" . $code . "' AND currency = '" . $code . "' ORDER BY addtime DESC";
    $re = $db->query($query_rate);
    $arr_rate = array();
    while ($row = $re->fetch()) {
        $rate = 1 / $row['rate'];
        if ($rate < 1) {
            $rate = number_format($rate, 9, '.', '');
        } else {
            $rate = number_format($rate, 0, '.', '');
        }
        $arr_rate[$row['currency_change']] = $rate;
    }

    $count = 0;
    foreach ($arr_money as $key => $value) {
        $valuerate = (!empty($arr_rate[$key])) ? $arr_rate[$key] : "";
        $xtpl->assign('currency', $valuerate);
        $class = ($count % 2) ? "" : "second";
        $xtpl->assign('class', $class);
        $xtpl->assign('code', $code);
        $xtpl->assign('money', $key);
        $xtpl->parse('main.loopmoney');
        $count++;
    }
}

//showlist

$stt = 0;
$re = $db->query("SELECT id, currency, currency_change , rate, addtime FROM " . $db_config['prefix'] . "_" . $module_data . "_rate WHERE currency_change ='" . $code . "' AND edittime between " . $dateview . " AND " . $dateview1 . " ORDER BY addtime DESC ");

while ($row = $re->fetch()) {
    if (!empty($row)) {
        $row['link_del'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delrate&id=" . $row['id'];
        $row['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&code=" . $code . "&id=" . $row['id'];
        $row['adddate'] = date("d/m/Y H:i ", $row['addtime']);
        $class = ($stt % 2) ? "second" : "";
        if ($row['rate'] < 1) {
            $row['rate'] = number_format($row['rate'], 9, '.', ' ');
        }
        $xtpl->assign('class', $class);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.data.row');
        $stt++;
    }
}

$xtpl->assign('id', $id);
$xtpl->assign('code', $code);
$xtpl->assign('URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delrate");
$xtpl->assign('URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&code=" . $code . "");
$xtpl->assign('URL_MONEY', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=money");
$xtpl->assign('action_getrate', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rate&getrate=1");
$xtpl->parse('main.data');
$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
