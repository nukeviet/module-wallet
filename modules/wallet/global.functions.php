<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

/**
 * get_display_money()
 *
 * @param mixed $amount
 * @param integer $digis
 * @param string $dec_point
 * @param string $thousan_step
 * @return
 */
function get_display_money($amount, $digis = 2, $dec_point = ',', $thousan_step = '.')
{
    $amount = number_format($amount, intval($digis), $dec_point, $thousan_step);
    $amount = rtrim($amount, '0');
    $amount = rtrim($amount, $dec_point);
    return $amount;
}

/**
 * get_db_money()
 *
 * @param mixed $amount
 * @param mixed $currency
 * @return
 */
function get_db_money($amount, $currency)
{
    if ($currency == 'VND') {
        return round($amount);
    } else {
        return round($amount, 2);
    }
}

$global_array_color_month = array(
    1 => '#DC143C',
    2 => '#8B4789',
    3 => '#4B0082',
    4 => '#27408B',
    5 => '#33A1C9',
    6 => '#2F4F4F',
    7 => '#008B45',
    8 => '#556B2F',
    9 => '#CD950C',
    10 => '#CD6600',
    11 => '#EE5C42',
    12 => '#EE0000',
);

// Tiền tệ hệ thống sử dụng
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money_sys";
$global_array_money_sys = $nv_Cache->db($sql, 'code', $module_name);

// Các cổng thanh toán đang kích hoạt
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_payment WHERE active = 1 ORDER BY weight ASC';
$global_array_payments = $nv_Cache->db($sql, 'payment', $module_name);

$global_array_transaction_status = array(
    0 => $lang_module['transaction_status0'],
    1 => $lang_module['transaction_status1'],
    2 => $lang_module['transaction_status2'],
    3 => $lang_module['transaction_status3'],
    4 => $lang_module['transaction_status4'],
    5 => $lang_module['transaction_status5']
);

$global_array_transaction_type = array(
    '0' => $lang_module['status_sub0'],
    '1' => $lang_module['status_sub1'],
    '2' => $lang_module['status_sub2'],
    '4' => $lang_module['status_sub4']
);
