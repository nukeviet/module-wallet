<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET'))
    die('Stop!!!');

$arr_info = "";

if (!defined('NV_IS_USER')) {
    $redirect = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op, true);
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_redirect_encrypt($redirect));
}

if ($nv_Request->get_title('type', 'post,get', 0) == "checkrate") {
    // Kiem tra ti gia
    $money1 = $nv_Request->get_title('money1', 'post,get', '');
    $money2 = $nv_Request->get_title('money2', 'post,get', '');

    // Kiểm tra loại tiền đầu vào
    if (!isset($global_array_money_sys[$money1])) {
        nv_htmlOutput(sprintf($lang_module['exchange_error_money'], $money1));
    }
    if (!isset($global_array_money_sys[$money2])) {
        nv_htmlOutput(sprintf($lang_module['exchange_error_money'], $money2));
    }

    // Hai loại tiền phải khác nhau
    if ($money1 == $money2) {
        nv_htmlOutput($lang_module['exchange_error_equal_money']);
    }

    $exchange = nv_wallet_checkRate($money1, $money2);
    if ($exchange === false) {
        $exchange = $lang_module['norate'];
    } else {
        $exchange = $lang_module['curentrate'] . ": 1 " . $money1 . " = " . get_display_money($exchange, 9) . " " . $money2;
    }
    $contents = $exchange;
} elseif ($nv_Request->get_title('type', 'post,get', 0) == "tinhtoan") {
    // Tinh toan so luong tien quy doi
    $money1 = $nv_Request->get_title('money1', 'post,get', 0);
    $money2 = $nv_Request->get_title('money2', 'post,get', 0);

    // Kiểm tra loại tiền đầu vào
    if (!isset($global_array_money_sys[$money1])) {
        nv_htmlOutput(sprintf($lang_module['exchange_error_money'], $money1));
    }
    if (!isset($global_array_money_sys[$money2])) {
        nv_htmlOutput(sprintf($lang_module['exchange_error_money'], $money2));
    }

    // Hai loại tiền phải khác nhau
    if ($money1 == $money2) {
        nv_htmlOutput($lang_module['exchange_error_equal_money']);
    }

    $totalmoneyexchange = $nv_Request->get_float('totalmoneyexchange', 'post,get', 0);
    if ($totalmoneyexchange <= 0) {
        nv_htmlOutput($lang_module['exchange_error_money_amount']);
    }

    $totalmoneyexchange = nv_wallet_tinhtoan($money1, $money2, $totalmoneyexchange);

    if ($totalmoneyexchange === false) {
        $totalmoneyexchange = $lang_module['norate'];
    } else {
        $totalmoneyexchange = " = " . get_display_money($totalmoneyexchange) . " " . $money2;
    }

    $contents = $totalmoneyexchange;
} elseif ($nv_Request->get_title('type', 'post,get', 0) == "exchange") {
    // Tien hanh quy doi
    $money1 = $nv_Request->get_title('money1', 'post,get', 0);
    $money2 = $nv_Request->get_title('money2', 'post,get', 0);

    // Kiểm tra loại tiền đầu vào
    if (!isset($global_array_money_sys[$money1])) {
        nv_htmlOutput(sprintf($lang_module['exchange_error_money'], $money1));
    }
    if (!isset($global_array_money_sys[$money2])) {
        nv_htmlOutput(sprintf($lang_module['exchange_error_money'], $money2));
    }

    // Hai loại tiền phải khác nhau
    if ($money1 == $money2) {
        nv_htmlOutput($lang_module['exchange_error_equal_money']);
    }

    $totalmoneyexchangebefor = $nv_Request->get_float('totalmoneyexchange', 'post,get', 0);
    if ($totalmoneyexchangebefor <= 0) {
        nv_htmlOutput($lang_module['exchange_error_money_amount']);
    }

    $exchange = nv_wallet_checkRate($money1, $money2);

    if ($exchange !== false) {
        // Thao tac kiem tra tien
        $arr_info = getInfoMoney($money1);

        if ($arr_info['moneytotalnotformat'] < $totalmoneyexchangebefor) {
            $contents = $lang_module['notexchange'];
        } else {
            // So tien quy doi
            $totalmoneyexchangeend = nv_wallet_tinhtoan($money1, $money2, $totalmoneyexchangebefor);
            if ($totalmoneyexchangeend === false) {
                $contents = $lang_module['notexchange1'];
            } else {
                $check_exchange = nv_wallet_exchange($user_info['userid'], $money2, $totalmoneyexchangeend, $money1, $totalmoneyexchangebefor);
                if ($check_exchange) {
                    $contents = 'OK';
                } else {
                    $contents = $lang_module['exchange_system_error'];
                }
            }
        }
    } else {
        $contents = $lang_module['notexchange1'];
    }
} else {
    // Lay tong so tien hien co
    $money1 = $nv_Request->get_title('money1', 'post,get', '');
    $money2 = $nv_Request->get_title('money2', 'post,get', '');
    $arr_info1 = getInfoMoney($money1);
    $arr_info2 = getInfoMoney($money2);
    $contents = $arr_info1['money_total'] . ' ' . $money1 . '|' . $arr_info2['money_total'] . ' ' . $money2;
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
