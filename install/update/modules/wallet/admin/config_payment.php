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

$page_title = $lang_module['config_discount'];

$array_provider = array(
    'VNP' => 'Vinaphone',
    'VMS' => 'Mobifone',
    'VTT' => 'Viettel',
    'FPT' => 'FPT',
    'VTC' => 'VTC Vcoin',
    'MGC' => 'MegaCard');

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);

$array = array('payment' => 'vnptepay', 'data' => array());

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_payment_discount WHERE payment = ' . $db->quote($array['payment']);
$result = $db->query($sql);

$error = '';
$array_key = array();

while ($row = $result->fetch()) {
    $key = md5($array['payment'] . $row['revenue_from'] . $row['revenue_to']);

    if (!isset($array['data'][$key])) {
        $array['data'][$key] = array(
            'revenue_from' => floatval($row['revenue_from']),
            'revenue_to' => floatval($row['revenue_to']),
            'provider' => array());
    }

    $array['data'][$key]['provider'][$row['provider']] = floatval($row['discount']);
}

if ($nv_Request->isset_request('btnsubmit', 'post')) {
    $ids = $nv_Request->get_typed_array('ids', 'post', 'int', array());

    unset($array['data']);

    foreach ($ids as $id) {
        $array['data'][$id] = array(
            'revenue_from' => $nv_Request->get_float('revenue_from_' . $id, 'post', 0),
            'revenue_to' => $nv_Request->get_float('revenue_to_' . $id, 'post', 0),
            'provider' => array());

        foreach ($array_provider as $key => $provider) {
            $array_key[] = md5($array['payment'] . $array['data'][$id]['revenue_from'] . $array['data'][$id]['revenue_to'] . $key);
            $array['data'][$id]['provider'][$key] = $nv_Request->get_float('provider_' . $key . '_' . $id, 'post', 0);
        }

        // Kiểm tra lỗi
        if (empty($array['data'][$id]['revenue_to'])) {
            $error = $lang_module['cfg_payment_error_to'];
        } elseif (sizeof(($a = array_filter($array['data'][$id]['provider']))) == 0) {
            $error = $lang_module['cfg_payment_error_discount'];
        } elseif (max($array['data'][$id]['provider']) >= 100 or min($array['data'][$id]['provider']) < 0) {
            $error = $lang_module['cfg_payment_error_discount_value'];
        }
    }

    if (empty($error) and sizeof(($a = array_unique($array_key))) != (sizeof($array['data']) * sizeof($array_provider))) {
        $error = $lang_module['cfg_payment_error_duplicate'];
    }

    if (empty($error)) {
        // Xóa cấu hình cũ
        $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_payment_discount WHERE payment = :payment');
        $sth->bindParam(':payment', $array['payment'], PDO::PARAM_STR);
        $sth->execute();

        // Thiết lập lại cấu hình mới
        if (!empty($array['data'])) {
            foreach ($array['data'] as $row) {
                foreach ($row['provider'] as $key => $value) {
                    $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_payment_discount ( payment, revenue_from, revenue_to, provider, discount ) VALUES( :payment, :revenue_from, :revenue_to, :provider, :discount )');
                    $sth->bindParam(':payment', $array['payment'], PDO::PARAM_STR);
                    $sth->bindParam(':revenue_from', $row['revenue_from'], PDO::PARAM_INT);
                    $sth->bindParam(':revenue_to', $row['revenue_to'], PDO::PARAM_INT);
                    $sth->bindParam(':provider', $key, PDO::PARAM_STR);
                    $sth->bindParam(':discount', $value, PDO::PARAM_INT);
                    $sth->execute();
                }
            }
        }
    }
}

$xtpl->assign('CONFIG_WEIGHT_COUNT', sizeof($array['data']));

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$i = 0;
foreach ($array['data'] as $row) {
    $xtpl->assign('KEY', $i++);
    $xtpl->assign('REVENUE_FROM', $row['revenue_from']);
    $xtpl->assign('REVENUE_TO', $row['revenue_to']);

    foreach ($array_provider as $key => $provider) {
        $xtpl->assign('PROVIDER', $key);
        $xtpl->assign('DISCOUNT', isset($row['provider'][$key]) ? $row['provider'][$key] : 0);

        $xtpl->parse('main.loop.provider');
    }

    $xtpl->parse('main.loop');
}

foreach ($array_provider as $key => $provider) {
    $xtpl->assign('PROVIDER_KEY', $key);
    $xtpl->assign('PROVIDER', $provider);

    $xtpl->parse('main.provider');
    $xtpl->parse('main.providerJS');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
