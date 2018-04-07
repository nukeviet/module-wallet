<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN'))
    die('Stop!!!');

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$allow_func = array(
    'main',
    'config',
    'transaction',
    'historyexchange',
    'usercontent',
    'money',
    'delmoney',
    'delrate',
    'addacount',
    'exchange',
    'payport',
    'actpay',
    'viewtransaction',
    'users',
    'changepay',
    'sms',
    'epay',
    'nganluong',
    'config_sms',
    'statistics',
    'add_transaction',
    'config_payment',
    'order-list'
);

/**
 * getInfoUser()
 *
 * @param mixed $userid
 * @return
 */
function getInfoUser($userid)
{
    global $db_config, $db, $module_data;

    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money WHERE userid = " . $userid;
    $query = $db->query($sql);
    $arr_infoacount = array();
    while ($row = $query->fetch()) {
        $arr_infoacount['userid'] = $row['userid'];
        $arr_infoacount['money_total'] = $row['money_total'];
        $arr_infoacount['note'] = $row['note'];
        $arr_infoacount['money_unit'] = $row['money_unit'];
    }
    return $arr_infoacount;
}

/**
 * update_money()
 *
 * @param mixed $userid tài khoản tác động
 * @param mixed $money số tiền tác động
 * @param string $money_unit loại tiền
 * @param mixed $currTranStatus trạng thái giao dịch hiện tại
 * @param mixed $oldTranStatus trạng thái giao dịch trước đó nếu có
 * @param mixed $status cộng hay trừ tiền
 * @return
 */
function update_money($userid, $money, $money_unit = 'VND', $currTranStatus, $oldTranStatus, $status)
{
    global $db, $db_config, $module_data;

    $_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_money WHERE userid=' . $userid . ' AND money_unit=' . $db->quote($money_unit);
    $_query = $db->query($_sql);
    $check = $_query->rowCount();

    if ($check == 0) {
        // Không tác động tới tài khoản mà tài khoản chưa có thì không làm gì cả
        if ($currTranStatus != 4) {
            return true;
        }
        // chưa có thông tin -> insert
        $stmt = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_money (
            userid, created_time, created_userid, status, money_unit, money_in, money_out,
            money_total, note, tokenkey
        ) VALUES (
            :userid, :created_time, :created_userid, :status, :money_unit, :money_in, :money_out,
            :money_total, :note, :tokenkey
        )');

        $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
        $stmt->bindValue(':created_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $stmt->bindValue(':created_userid', 0, PDO::PARAM_INT);
        $stmt->bindValue(':status', 1, PDO::PARAM_INT);
        $stmt->bindValue(':money_unit', $money_unit, PDO::PARAM_STR);
        $stmt->bindValue(':money_in', $money, PDO::PARAM_INT);
        $stmt->bindValue(':money_out', 0, PDO::PARAM_INT);
        $stmt->bindValue(':money_total', $money, PDO::PARAM_INT);
        $stmt->bindValue(':note', '', PDO::PARAM_STR);
        $stmt->bindValue(':tokenkey', '', PDO::PARAM_STR);

        $exc = $stmt->execute();
    } else {
        $row = $_query->fetch();
        $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_money SET
            money_in=:money_in, money_total=:money_total
        WHERE userid=' . $userid . ' AND money_unit=' . $db->quote($money_unit));

        if ($status == 1) {
            // Cộng tiền
            if ($currTranStatus == 4 and $oldTranStatus != 4) {
                // Cộng vào nếu trước đó chưa cộng
                $stmt->bindValue(':money_in', ($row['money_in'] + $money), PDO::PARAM_INT);
                $stmt->bindValue(':money_total', ($row['money_total'] + $money), PDO::PARAM_INT);
            } elseif ($currTranStatus != 4 and $oldTranStatus == 4) {
                // Trừ ra nếu trước đó đã cộng
                $stmt->bindValue(':money_in', ($row['money_in'] - $money), PDO::PARAM_INT);
                $stmt->bindValue(':money_total', ($row['money_total'] - $money), PDO::PARAM_INT);
            } else {
                return true;
            }
        } elseif ($status == -1) {
            // Trừ tiền
            if ($currTranStatus == 4 and $oldTranStatus != 4) {
                // Trừ ra nếu trước đó chưa trừ
                $stmt->bindValue(':money_in', ($row['money_in'] - $money), PDO::PARAM_INT);
                $stmt->bindValue(':money_total', ($row['money_total'] - $money), PDO::PARAM_INT);
            } elseif ($currTranStatus != 4 and $oldTranStatus == 4) {
                // Cộng vào nếu trước đó đã trừ
                $stmt->bindValue(':money_in', ($row['money_in'] + $money), PDO::PARAM_INT);
                $stmt->bindValue(':money_total', ($row['money_total'] + $money), PDO::PARAM_INT);
            } else {
                return true;
            }
        }

        $exc = $stmt->execute();
    }
}

define('NV_IS_FILE_ADMIN', true);
