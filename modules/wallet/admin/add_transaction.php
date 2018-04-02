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

$page_title = $lang_module['add_transaction'];

$row = array();
$error = array();

if ($nv_Request->isset_request('submit', 'post')) {
    // Tài khoản tác động tới
    $row['account'] = $nv_Request->get_title('account', 'post', '');
    // Người giao dịch
    $row['customer'] = $nv_Request->get_title('customer', 'post', '');

    $row['money_transaction'] = $nv_Request->get_title('money_transaction', 'post', '');
    $row['money_transaction'] = floatval(str_replace(',', '', $row['money_transaction']));

    $row['transaction_status'] = $nv_Request->get_int('transaction_status', 'post', 1);
    $row['transaction_info'] = $nv_Request->get_title('transaction_info', 'post', '');
    $row['money_unit'] = $nv_Request->get_title('money_unit', 'post', '');

    // Xác định tài khoản tác động
    $sql = "SELECT userid, username, first_name, last_name, email FROM " . NV_USERS_GLOBALTABLE . " WHERE username=:username";
    $sth = $db->prepare($sql);
    $sth->bindParam(':username', $row['account'], PDO::PARAM_STR);
    $sth->execute();
    $account_info = $sth->fetch();

    // Xác định thành viên giao dịch
    $sql = "SELECT userid, username, first_name, last_name, email FROM " . NV_USERS_GLOBALTABLE . " WHERE username=:username";
    $sth = $db->prepare($sql);
    $sth->bindParam(':username', $row['customer'], PDO::PARAM_STR);
    $sth->execute();
    $customer_info = $sth->fetch();

    if (empty($row['account'])) {
        $error[] = $lang_module['error_required_customer'];
    } elseif (empty($account_info)) {
        $error[] = sprintf($lang_module['error_exists_customer'], $row['account']);
    } elseif (!empty($row['customer']) and empty($customer_info)) {
        $error[] = sprintf($lang_module['error_exists_customer'], $row['customer']);
    } elseif ($row['money_transaction'] <= 0) {
        $error[] = $lang_module['error_required_money_transaction'];
    } elseif (empty($row['transaction_info'])) {
        $error[] = $lang_module['error_required_transaction_info'];
    } elseif (!isset($global_array_money_sys[$row['money_unit']])) {
        $error[] = $lang_module['addacc_error_typymoney'];
    } elseif (!isset($global_array_transaction_status[$row['transaction_status']])) {
        $error[] = $lang_module['addtran_error_transaction_status'];
    }

    if (empty($error)) {
        try {
            $row['userid'] = $account_info['userid'];
            $row['money_total'] = $row['money_transaction'];
            $row['money_fee'] = 0;
            $row['money_net'] = $row['money_transaction'];
            $row['money_discount'] = 0;
            $row['money_revenue'] = $row['money_transaction'];

            $row['created_time'] = NV_CURRENTTIME;
            $row['status'] = 1;

            if (empty($customer_info)) {
                $row['adminid'] = $admin_info['admin_id'];
                $row['customer_id'] = 0;
                $row['customer_name'] = '';
                $row['customer_email'] = '';
            } else {
                $row['adminid'] = 0;
                $row['customer_id'] = $customer_info['userid'];
                $row['customer_name'] = nv_show_name_user($customer_info['first_name'], $customer_info['last_name'], $customer_info['username']);
                $row['customer_email'] = $customer_info['email'];
            }

            $row['customer_phone'] = '';
            $row['customer_address'] = '';
            $row['customer_info'] = '';
            $row['transaction_id'] = '';
            $row['transaction_data'] = '';
            $row['payment'] = '';
            $row['tokenkey'] = '';
            $row['transaction_time'] = 0;
            if ($row['transaction_status'] == 4) {
                $row['transaction_time'] = NV_CURRENTTIME;
            }

            $stmt = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_transaction (
                created_time, status, money_unit, money_total, money_net, money_discount,
                money_revenue, userid, adminid, customer_id, customer_name, customer_email, customer_phone,
                customer_address, customer_info, transaction_id, transaction_status, transaction_time,
                transaction_info, transaction_data, payment, tokenkey)
            VALUES (
                :created_time, :status, :money_unit, :money_total, :money_net, :money_discount,
                :money_revenue, :userid, :adminid, :customer_id, :customer_name, :customer_email, :customer_phone,
                :customer_address, :customer_info, :transaction_id, :transaction_status, :transaction_time,
                :transaction_info, :transaction_data, :payment, :tokenkey
            )');

            $stmt->bindParam(':created_time', $row['created_time'], PDO::PARAM_INT);
            $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
            $stmt->bindParam(':money_unit', $row['money_unit'], PDO::PARAM_STR);
            $stmt->bindParam(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt->bindParam(':adminid', $row['adminid'], PDO::PARAM_INT);
            $stmt->bindParam(':customer_id', $row['customer_id'], PDO::PARAM_INT);
            $stmt->bindParam(':customer_name', $row['customer_name'], PDO::PARAM_STR);
            $stmt->bindParam(':customer_email', $row['customer_email'], PDO::PARAM_STR);
            $stmt->bindParam(':customer_phone', $row['customer_phone'], PDO::PARAM_STR);
            $stmt->bindParam(':customer_address', $row['customer_address'], PDO::PARAM_STR);
            $stmt->bindParam(':transaction_id', $row['transaction_id'], PDO::PARAM_STR);
            $stmt->bindParam(':transaction_status', $row['transaction_status'], PDO::PARAM_INT);
            $stmt->bindParam(':transaction_time', $row['transaction_time'], PDO::PARAM_INT);
            $stmt->bindParam(':transaction_data', $row['transaction_data'], PDO::PARAM_STR);
            $stmt->bindParam(':payment', $row['payment'], PDO::PARAM_STR);
            $stmt->bindParam(':tokenkey', $row['tokenkey'], PDO::PARAM_STR);

            $stmt->bindParam(':money_total', $row['money_total'], PDO::PARAM_STR);
            $stmt->bindParam(':money_net', $row['money_net'], PDO::PARAM_STR);
            $stmt->bindParam(':money_discount', $row['money_discount'], PDO::PARAM_STR);
            $stmt->bindParam(':money_revenue', $row['money_revenue'], PDO::PARAM_STR);
            $stmt->bindParam(':customer_info', $row['customer_info'], PDO::PARAM_STR, strlen($row['customer_info']));
            $stmt->bindParam(':transaction_info', $row['transaction_info'], PDO::PARAM_STR, strlen($row['transaction_info']));

            $exc = $stmt->execute();
            if ($exc) {
                $message = '';
                //nếu xác nhận đã thanh toán thì cập nhật số tiền vào tài khoản
                if ($row['transaction_status'] == 4) {
                    update_money($row['userid'], $row['money_total'], $row['money_unit'], 4, 0, 1);
                    $message = sprintf($lang_module['email_transaction_message'], $row['money_total']);
                } else {
                    $message = sprintf($lang_module['email_transaction_message1'], $row['money_total']);
                    $message .= $lang_module['email_transaction_message3'];
                }
                $message .= $lang_module['email_transaction_message4'];

                $subject = $lang_module['email_transaction_title'] . nv_date('h:s d/m/Y', NV_CURRENTTIME);

                if (!empty($row['customer_email'])) {
                    $send = nv_sendmail($global_config['site_email'], $row['customer_email'], $subject, $message);
                    if ($send) {
                        $info = $lang_module['send_mail_success'] . "<br /><br />\n";
                    } else {
                        $info = $lang_module['send_mail_error'] . "<br /><br />\n";
                    }
                }

                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=transaction');
            }
        } catch (PDOException $e) {
            $error[] = $e->getMessage();
        }
    }
} else {
    $row['transaction_status'] = 4;
    $row['money_unit'] = '';
    $row['money_transaction'] = 0;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('OP', $op);

if ($row['money_transaction'] <= 0) {
    $row['money_transaction'] = '';
} else {
    $row['money_transaction'] = get_display_money($row['money_transaction'], 2, '.', ',');
}

$xtpl->assign('ROW', $row);

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

foreach ($global_array_transaction_status as $k => $v) {
    if ($k != 0) {
        $transaction_status = array(
            'key' => $k,
            'title' => $v,
            'selected' => $k == $row['transaction_status'] ? ' selected="selected"' : ''
        );
        $xtpl->assign('TRANSACTION_STATUS', $transaction_status);
        $xtpl->parse('main.transaction_status');
    }
}

foreach ($global_array_money_sys as $money_sys) {
    $money_unit = array(
        'key' => $money_sys['code'],
        'title' => $money_sys['code'],
        'selected' => $money_sys['code'] == $row['money_unit'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('MONEY_UNIT', $money_unit);
    $xtpl->parse('main.money_unit');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
