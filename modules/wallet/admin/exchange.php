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

$toMoney = $nv_Request->get_title('m', 'get', '');
if (empty($toMoney)) {
    $toMoney = array_keys($global_array_money_sys);
    $toMoney = current($toMoney);
} elseif (!isset($global_array_money_sys[$toMoney])) {
    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
}

// Xác định tỉ giá quy đổi ra các đồng tiền khác
$arr_rate = array();
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE than_unit=" . $db->quote($toMoney) . " ORDER BY time_update DESC";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $arr_rate[$row['money_unit']] = array(
        'from' => $row['exchange_from'],
        'to' => $row['exchange_to'],
        'time_update' => $row['time_update']
    );
}

// Lưu mới, cập nhật tỉ giá
if ($nv_Request->get_title('submit', 'post')) {
    $applyopposite = $nv_Request->get_int('applyopposite', 'post', 0);
    $array_exchange_from = $nv_Request->get_typed_array('exchange_from', 'post', 'float', array());
    $array_exchange_to = $nv_Request->get_typed_array('exchange_to', 'post', 'float', array());

    foreach ($global_array_money_sys as $moneysys) {
        if ($moneysys['code'] != $toMoney and isset($array_exchange_from[$moneysys['code']]) and isset($array_exchange_to[$moneysys['code']])) {
            $exchange_from = floatval($array_exchange_from[$moneysys['code']]);
            $exchange_to = floatval($array_exchange_to[$moneysys['code']]);
            if ($exchange_from > 0 and $exchange_to) {
                try {
                    // Thêm mới nếu chưa có
                    $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_exchange (
                        money_unit, than_unit, exchange_from, exchange_to, time_update, status
                    ) VALUES (
                        " . $db->quote($moneysys['code']) . ", " . $db->quote($toMoney) . ",
                        " . $exchange_from . ", " . $exchange_to . ", " . NV_CURRENTTIME . ", 1
                    )";
                    $db->query($sql);
                } catch (Exception $e) {
                    // Cập nhật tỉ giá cũ
                    $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_exchange SET
                        exchange_from=" . $exchange_from . ",
                        exchange_to=" . $exchange_to . ",
                        time_update=" . NV_CURRENTTIME . "
                    WHERE money_unit=" . $db->quote($moneysys['code']) . " AND than_unit=" . $db->quote($toMoney);
                    $db->query($sql);

                    // Lịch sử tỉ giá
                    if (isset($arr_rate[$moneysys['code']])) {
                        $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_exchange_log (
                            money_unit, than_unit, exchange_from, exchange_to, time_begin, time_end
                        ) VALUES (
                            " . $db->quote($moneysys['code']) . ",
                            " . $db->quote($toMoney) . ",
                            " . $arr_rate[$moneysys['code']]['from'] . ",
                            " . $arr_rate[$moneysys['code']]['to'] . ",
                            " . $arr_rate[$moneysys['code']]['time_update'] . ",
                            " . NV_CURRENTTIME . "
                        )";
                        $db->query($sql);
                    }
                }

                // Áp dụng cho chiều ngược lại
                if ($applyopposite) {
                    $exchange_info = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE money_unit=" . $db->quote($toMoney) . " AND than_unit=" . $db->quote($moneysys['code']))->fetch();
                    if (empty($exchange_info)) {
                        $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_exchange (
                            money_unit, than_unit, exchange_from, exchange_to, time_update, status
                        ) VALUES (
                            " . $db->quote($toMoney) . ", " . $db->quote($moneysys['code']) . ",
                            " . $exchange_to . ", " . $exchange_from . ", " . NV_CURRENTTIME . ", 1
                        )";
                        $db->query($sql);
                    } else {
                        // Cập nhật tỉ giá cũ
                        $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_exchange SET
                            exchange_from=" . $exchange_to . ",
                            exchange_to=" . $exchange_from . ",
                            time_update=" . NV_CURRENTTIME . "
                        WHERE money_unit=" . $db->quote($toMoney) . " AND than_unit=" . $db->quote($moneysys['code']);
                        $db->query($sql);

                        // Lịch sử tỉ giá
                        $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_exchange_log (
                            money_unit, than_unit, exchange_from, exchange_to, time_begin, time_end
                        ) VALUES (
                            " . $db->quote($toMoney) . ",
                            " . $db->quote($moneysys['code']) . ",
                            " . $exchange_info['exchange_from'] . ",
                            " . $exchange_info['exchange_to'] . ",
                            " . $exchange_info['time_update'] . ",
                            " . NV_CURRENTTIME . "
                        )";
                        $db->query($sql);
                    }
                }
            }
        }
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Exchage rate update', $toMoney, $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&m=' . $toMoney);
}

$xtpl = new XTemplate("exchange.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('TO_MONEY_CODE', $toMoney);
$xtpl->assign('TO_MONEY_TITLE', $global_array_money_sys[$toMoney]['currency']);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . '&amp;m=' . $toMoney);

// Xuất các loại tiền quản lý
foreach ($global_array_money_sys as $moneysys) {
    if ($moneysys['code'] != $toMoney) {
        $arr_money[$moneysys['code']] = $moneysys['currency'];
        $moneysys['link'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . '&amp;m=' . $moneysys['code'];
        $xtpl->assign('MONEYSYS', $moneysys);
        $xtpl->parse('main.moneysys');

        $loopmoney = array(
            'key' => $moneysys['code'],
            'currency' => $moneysys['currency'],
            'value_from' => isset($arr_rate[$moneysys['code']]) ? $arr_rate[$moneysys['code']]['from'] : '',
            'value_to' => isset($arr_rate[$moneysys['code']]) ? $arr_rate[$moneysys['code']]['to'] : ''
        );
        $xtpl->assign('LOOPMONEY', $loopmoney);
        $xtpl->parse('main.loopmoney_from');
        $xtpl->parse('main.loopmoney_to');
    }
}

// Danh sách tỷ giá theo loại tiền đã chọn
$result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE than_unit=" . $db->quote($toMoney) . " ORDER BY time_update DESC");
while ($row = $result->fetch()) {
    $row['link_del'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delrate&id=" . $row['id'];
    $row['time_update'] = date("d/m/Y H:i ", $row['time_update']);
    $row['exchange_from'] = get_display_money($row['exchange_from']);
    $row['exchange_to'] = get_display_money($row['exchange_to']);
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->assign('URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delrate");
$xtpl->assign('URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&m=" . $toMoney);
$xtpl->assign('ACTION_GETRATE', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=exchange&getrate=1");

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
