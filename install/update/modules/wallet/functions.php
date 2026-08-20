<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

define('NV_IS_MOD_WALLET', true);

$module_config[$module_name]['minimum_amount'] = !empty($module_config[$module_name]['minimum_amount']) ? unserialize($module_config[$module_name]['minimum_amount']) : array();
$module_config[$module_name]['recharge_rate'] = !empty($module_config[$module_name]['recharge_rate']) ? unserialize($module_config[$module_name]['recharge_rate']) : array();

/**
 * @param mixed $userid
 * @param mixed $money_unit
 * @param mixed $money
 * @param string $note_creat
 * @return
 */
function nv_wallet_money_in($userid, $money_unit, $money, $note_creat = '')
{
    global $db, $db_config, $module_data, $module_name, $nv_Cache;

    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money WHERE userid=" . $userid . " AND money_unit=" . $db->quote($money_unit);
    $result = $db->query($sql);

    $return = false;

    if ($result->rowCount()) {
        $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_money SET
            money_in= money_in+" . $money . ",
            money_total = money_total+" . $money . "
        WHERE userid= " . $userid . " AND money_unit=" . $db->quote($money_unit);
        $return = $db->exec($sql);
    } else {
        $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money (
            userid, created_time, created_userid, status, money_unit, money_in, money_out, money_total, note, tokenkey
        ) VALUES (
            " . $userid . ", " . NV_CURRENTTIME . ", 0, 1, " . $db->quote($money_unit) . ", '" . $money . "', 0, '" . $money . "', " . $db->quote($note_creat) . ", ''
        )";
        $return = $db->exec($sql);
    }

    $nv_Cache->delMod($module_name);

    return $return;
}

/**
 * @param mixed $userid -- ID thành viên
 * @param mixed $money_unit -- Loại tiền cộng vào
 * @param mixed $moneyexchange -- Số tiền cộng vào
 * @param mixed $moneysub -- Loại tiền trừ ra
 * @param mixed $totalmoneyexchangebefor -- Số tiền trừ ra
 * @return
 */
function nv_wallet_exchange($userid, $money_unit, $moneyexchange, $moneysub, $totalmoneyexchangebefor)
{
    global $db, $db_config, $global_config, $module_data, $lang_module, $module_name;

    // Trừ tiền trước khi quy đổi
    $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_money SET
        money_total=money_total-" . $totalmoneyexchangebefor . ",
        money_out=money_out+" . $totalmoneyexchangebefor . "
    WHERE userid=" . $userid . " AND money_unit=" . $db->quote($moneysub));
    if (!$check) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Error Subtract Money', 'UID: ' . $userid . ', AMOUNT: ' . $totalmoneyexchangebefor . ' ' . $moneysub, 0);
        return false;
    }

    // Ghi log giao dịch trừ tiền
    $transinfo = sprintf($lang_module['exchange_transition_mess_sub'], $money_unit);
    $check = updateTransaction($totalmoneyexchangebefor, $moneysub, -1, $userid, $transinfo);
    if (!$check) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Error Save Transaction Subtract', 'UID: ' . $userid . ', AMOUNT: ' . $totalmoneyexchangebefor . ' ' . $moneysub, 0);
    }

    // Kiểm tra và cộng tiền
    $checkReturn = nv_wallet_money_in($userid, $money_unit, $moneyexchange, 'Exchange auto creat');
    if (!$checkReturn) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Error Plus money', 'UID: ' . $userid . ', AMOUNT: ' . $moneyexchange . ' ' . $money_unit, 0);
    }
    $transinfo = sprintf($lang_module['exchange_transition_mess_plus'], $moneysub);
    $check = updateTransaction($moneyexchange, $money_unit, 1, $userid, $transinfo);
    if (!$check) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Error Save Transaction Plus', 'UID: ' . $userid . ', AMOUNT: ' . $moneyexchange . ' ' . $money_unit, 0);
    }

    return $checkReturn;
}

/**
 * @param mixed $money_unit
 * @return
 */
function getInfoMoney($money_unit)
{
    global $db, $db_config, $module_data, $user_info;

    $arr_temp = array();
    if (!defined('NV_IS_USER')) {
        $arr_temp['money_total'] = 0;
        $arr_temp['moneytotalnotformat'] = 0;
        return $arr_temp;
    }
    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money WHERE userid=" . $user_info['userid'] . " AND money_unit=" . $db->quote($money_unit);
    $result = $db->query($sql);

    if ($result->rowCount()) {
        while ($row = $result->fetch()) {
            $arr_temp = array(
                'userid' => $row['userid'],
                'created_time' => date("d/m/Y", $row['created_time']),
                'created_userid' => $row['created_userid'],
                'status' => $row['status'],
                'money_unit' => $row['money_unit'],
                'money_in' => get_display_money($row['money_in']),
                'money_out' => get_display_money($row['money_out']),
                'money_total' => get_display_money($row['money_total']),
                'moneytotalnotformat' => $row['money_total'],
                'note' => $row['note']
            );
        }
    } else {
        $arr_temp['money_total'] = 0;
        $arr_temp['moneytotalnotformat'] = 0;
    }
    return $arr_temp;
}

/**
 * @param mixed $money1
 * @param mixed $money2
 * @return
 */
function nv_wallet_checkRate($money1, $money2)
{
    global $db, $db_config, $global_config, $module_data, $user_info;

    $sql = "SELECT exchange_from, exchange_to FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE money_unit=" . $db->quote($money1) . " AND than_unit=" . $db->quote($money2);
    $result = $db->query($sql);

    if ($result->rowCount()) {
        $row = $result->fetch();
        $exchange = array($row['exchange_from'], $row['exchange_to']);
    } else {
        $exchange = false;
    }
    return $exchange;
}

/**
 * @param mixed $money1
 * @param mixed $money2
 * @param mixed $totalmoneyexchange
 * @return
 */
function nv_wallet_tinhtoan($money1, $money2, $totalmoneyexchange)
{
    global $db, $db_config, $global_config, $module_data, $user_info;

    $sql = "SELECT exchange_from, exchange_to FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE money_unit=" . $db->quote($money1) . " AND than_unit=" . $db->quote($money2);
    $result = $db->query($sql);

    if ($result->rowCount()) {
        $row = $result->fetch();
        $totalmoneyexchange = floatval($totalmoneyexchange) * floatval($row['exchange_to'] / $row['exchange_from']);
    } else {
        $totalmoneyexchange = false;
    }
    return $totalmoneyexchange;
}

/**
 * @param mixed $moneyexchange
 * @param mixed $moneyunit
 * @param mixed $status
 * @param mixed $userid
 * @param mixed $transinfo
 * @return
 */
function updateTransaction($moneyexchange, $moneyunit, $status, $userid, $transinfo)
{
    global $db, $db_config, $module_data;

    $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (
        created_time, status, money_unit, money_total, money_net, money_discount, money_revenue, userid, adminid,
        customer_id, customer_name, customer_email, customer_phone, customer_address, customer_info, transaction_id,
        transaction_type, transaction_status, transaction_time, transaction_info, transaction_data, payment, provider, tokenkey
    ) VALUES (
        " . NV_CURRENTTIME . "," . $status . ", :money_unit, " . $moneyexchange . ", " . $moneyexchange . ", 0, 0,
        " . $userid . ", " . $userid . ", " . $userid . ", :customer_name, :customer_email, :customer_phone, :customer_address,
        :customer_info, :transaction_id, :transaction_type, :transaction_status, " . NV_CURRENTTIME . ", :transaction_info, :transaction_data,
        :payment, :provider, :tokenkey
    )";

    $data_insert = array();
    $data_insert['money_unit'] = $moneyunit;
    $data_insert['customer_name'] = '';
    $data_insert['customer_email'] = '';
    $data_insert['customer_phone'] = '';
    $data_insert['customer_address'] = '';
    $data_insert['customer_info'] = '';
    $data_insert['transaction_id'] = '0';
    $data_insert['transaction_type'] = -1;
    $data_insert['transaction_status'] = 4;
    $data_insert['transaction_info'] = $transinfo;
    $data_insert['transaction_data'] = '';
    $data_insert['payment'] = '';
    $data_insert['provider'] = '';
    $data_insert['tokenkey'] = '';

    return $db->insert_id($sql, 'id', $data_insert);
}

/**
 * @return array
 */
function getVietqrBanksV1()
{
    global $nv_Cache, $module_name;

    $cacheFile = NV_LANG_DATA . '_vietqr_banks_' . NV_CACHE_PREFIX . '.cache';
    $cacheTTL = 3600;
    if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
        $array_banks = json_decode($cache, true);
    } else {
        $array_banks = [];
        $banks = file_get_contents('https://api.vietqr.io/v1/banks');
        $banks = json_decode($banks, true);

        if (is_array($banks) and !empty($banks['data'])) {
            foreach ($banks['data'] as $bank) {
                if ($bank['vietqr'] > 1) {
                    // Ngân hàng quét mã được mới hiển thị
                    $array_banks[$bank['bin']] = $bank;
                }
            }
        }
        $nv_Cache->setItem($module_name, $cacheFile, json_encode($array_banks), $cacheTTL);
    }

    return $array_banks;
}

/**
 * @return array
 */
function getVietqrBanksV2()
{
    global $nv_Cache, $module_name;

    $cacheFile = NV_LANG_DATA . '_vietqrv2_banks_' . NV_CACHE_PREFIX . '.cache';
    $cacheTTL = 3600;
    if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
        $array_banks = json_decode($cache, true);
    } else {
        $array_banks = [];
        $banks = file_get_contents('https://api.vietqr.io/v2/banks');
        $banks = json_decode($banks, true);

        if (is_array($banks) and !empty($banks['data'])) {
            foreach ($banks['data'] as $bank) {
                if (!empty($bank['transferSupported'])) {
                    // Ngân hàng quét mã được mới hiển thị
                    $array_banks[$bank['bin']] = $bank;
                }
            }
        }
        $nv_Cache->setItem($module_name, $cacheFile, json_encode($array_banks), $cacheTTL);
    }

    return $array_banks;
}
