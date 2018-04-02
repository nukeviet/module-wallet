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

$page_title = $lang_module['exchange'];
$code = "";
$data = "";

//view rate
if ($nv_Request->get_string('code', 'get')) {
    $code = $nv_Request->get_string('code', 'get');
}
if ($nv_Request->get_string('getrate', 'post,get')) {
    $code = $nv_Request->get_string('code', 'post,get');
}

//addnew rate
if ($nv_Request->get_string('savecat', 'post,get')) {
    $code = $nv_Request->get_string('codecurent', 'post,get');
    $arr_currency = $nv_Request->get_array('currency', 'get,post', 'string');
    $arr_money_code = $nv_Request->get_array('money_code', 'get,post', 'string');
    $id_i = $nv_Request->get_int('save_rate', 'post,get', 0);
    if ($id_i > 0) {
        $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_exchange SET exchange = " . $arr_currency[0] . ", time_update = " . NV_CURRENTTIME . " WHERE id = " . $id_i . " ";
        $db->query($sql);
        $nv_Cache->delMod($module_name);
    } else {
        for ($i = 0; $i < count($arr_currency); $i++) {
            if ($arr_currency[$i] != "") {
                //kiem tra su ton tai cua tien te
                $re = $db->query("SELECT id, money_unit, than_unit, exchange, time_update FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE money_unit = '" . $arr_money_code[$i] . "' AND than_unit = '" . $code . "'");
                $arr_money = array();
                $row = $re->rowCount();
                list($id, $money_unit, $than_unit, $exchange, $time_update) = $re->fetch(3);
                if ($row == 0) {
                    $query = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_exchange (id,money_unit, than_unit, exchange,time_update, status) VALUES (NULL, " . $db->quote($arr_money_code[$i]) . ", " . $db->quote($code) . ", " . $arr_currency[$i] . "," . NV_CURRENTTIME . ",1 )";
                    $db->query($query);
                    $nv_Cache->delMod($module_name);
                } else {
                    //insert into exchange_log
                    $query = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_exchange_log (
                        log_id,money_unit, than_unit, exchange,time_begin, time_end
                    ) VALUES (
                        NULL, " . $db->quote($money_unit) . ", " . $db->quote($than_unit) . ", " . $exchange . "," . $time_update . "," . NV_CURRENTTIME . "
                    )";
                    $db->query($query);

                    //update exchange
                    $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_exchange SET exchange = " . $arr_currency[$i] . ", time_update = " . NV_CURRENTTIME . " WHERE id = " . $id . " ";
                    $db->query($sql);
                    $nv_Cache->delMod($module_name);
                }
            }
        }
    }

}

$xtpl = new XTemplate("exchange.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);

//show for add new

$re = $db->query("SELECT id, code, currency FROM " . $db_config['prefix'] . "_" . $module_data . "_money_sys ORDER BY id");
$arr_money = array();
while ($row = $re->fetch()) {
    if ($code == "") {
        $code = $row['code'];
    }
    if ($code == $row['code']) {
        $select = "selected=\"selected\"";
    } else {
        $select = "";
    }
    $xtpl->assign('selectted', $select);
    $link_change = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=exchange&id=" . $row['code'];
    $xtpl->assign('link_change', $link_change);
    $arr_money[$row['code']] = $row['currency'];
    $xtpl->assign('DATAMONEY', $row);
    $xtpl->parse('main.data.money');
}

unset($arr_money[$code]);
//parse for edit rate
if ($data != "" && isset($arr_money[$data['money_unit']])) {
    if ($data['rate'] < 1) {
        $data['rate'] = number_format($data['exchange'], 9, '.', ' ');
    }
    $xtpl->assign('currency', $data['exchange']);
    $xtpl->assign('code', $data['money_unit']);
    $xtpl->assign('money', $data['than_unit']);
    $xtpl->assign('id_save', $data['id']);
    $xtpl->parse('main.loopmoney');
} else {
    $query_rate = "SELECT money_unit,	than_unit, exchange FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE than_unit ='" . $code . "' AND money_unit != '" . $code . "' ORDER BY time_update DESC";
    $re = $db->query($query_rate);
    $arr_rate = array();
    while ($row = $re->fetch()) {
        $rate = 1 / $row['exchange'];
        if ($rate < 1) {
            $rate = number_format($rate, 9, '.', '');
        } else {
            $rate = number_format($rate, 0, '.', '');
        }
        $arr_rate[$row['than_unit']] = $rate;
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
$re = $db->query("SELECT id, money_unit, than_unit , exchange, time_update FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE than_unit ='" . $code . "' ORDER BY time_update DESC ");

while ($row = $re->fetch()) {
    if (!empty($row)) {
        $row['link_del'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delrate&id=" . $row['id'];
        $row['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&code=" . $code . "&id=" . $row['id'];
        $row['time_update'] = date("d/m/Y H:i ", $row['time_update']);
        $class = ($stt % 2) ? "second" : "";
        if ($row['exchange'] < 1) {
            $row['exchange'] = number_format($row['exchange'], 9, '.', ' ');
        }
        $xtpl->assign('class', $class);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.data.row');
        $stt++;
    }
}

$xtpl->assign('code', $code);
$xtpl->assign('URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delrate");
$xtpl->assign('URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&code=" . $code . "");
$xtpl->assign('action_getrate', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=exchange&getrate=1");
$xtpl->parse('main.data');

$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
