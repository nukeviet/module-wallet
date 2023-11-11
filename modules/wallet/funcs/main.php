<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET')) {
    die('Stop!!!');
}

use NukeViet\Http\Http;

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$url_checkout = [];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

// Nạp đúng số tiền nào đó
$pay_amount = $nv_Request->get_title('amount', 'get', '');
$pay_info = nv_substr($nv_Request->get_title('info', 'get', ''), 0, 250);
$pay_money = '';
if (preg_match('/^([0-9\.]+)\-([A-Z]{3})$/', $pay_amount, $m)) {
    if (!isset($global_array_money_sys[$m[2]])) {
        $pay_amount = '';
    } else {
        $pay_money = $m[2];
    }
} else {
    $pay_amount = '';
}

foreach ($global_array_payments as $row) {
    $row['currency_support'] = explode(',', $row['currency_support']);
    if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $row['payment'] . '.checkout_url.php') and (empty($pay_amount) or !empty($row['allowedoptionalmoney'])) and (empty($pay_money) or in_array($pay_money, $row['currency_support']))) {
        $payment_config = unserialize(nv_base64_decode($row['config']));
        $payment_config['paymentname'] = $row['paymentname'];
        $payment_config['domain'] = $row['domain'];

        $images_button = $row['images_button'];
        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=recharge/' . $row['payment'];
        if (!empty($pay_amount)) {
            $url .= '&amp;amount=' . $pay_amount;
        }
        if (!empty($pay_info)) {
            $url .= '&amp;info=' . urlencode($pay_info);
        }

        if (!empty($images_button) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $images_button)) {
            $images_button = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $images_button;
        }

        $url_checkout[] = [
            'payment' => $row['payment'],
            'name' => $row['paymentname'],
            'url' => $url,
            'images_button' => $images_button,
            'guide' => $row['bodytext']
        ];
    }
}

if (isset($array_op[1]) and $array_op[0] == 'sepay') {
    $page_url .= '&amp;' . NV_OP_VARIABLE . '=sepay/' . $array_op[1];
}
$canonicalUrl = getCanonicalUrl($page_url);

// Trang chờ thanh toán ATM
if (isset($array_op[1]) and $array_op[0] == 'sepay' and preg_match('/^(GD|WP)([0-9]{10})$/', $array_op[1], $m) and defined('NV_IS_USER') and isset($global_array_payments['sepay'])) {
    $nv_BotManager->setPrivate();

    $transaction_type = $m[1] == 'WP' ? 'pay' : 'recharge';
    $transaction_id = intval($m[2]);
    $payment = $global_array_payments['sepay'];
    $payment_config = unserialize(nv_base64_decode($payment['config'])) ?: [];

    $payment_config['account_no'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['account_no']));
    $payment_config['account_name'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['account_name']));
    $payment_config['acq_id'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['acq_id']));
    $payment_config['bank_branch'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(';', $payment_config['bank_branch']));

    $json_respon = [
        'continue' => false,
        'success' => false,
        'message' => '',
        'redirect' => '',
    ];
    $is_ajax = ($nv_Request->get_title('getStatus', 'post', '') === NV_CHECK_SESSION);

    // Lấy và chỉ chấp nhận giao dịch cùng người, có status=0
    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE
    id=" . $transaction_id . " AND customer_id=" . $user_info['userid'] . " AND transaction_type=-1";
    $transaction = $db->query($sql)->fetch();
    if (empty($transaction)) {
        if ($is_ajax) {
            $json_respon['message'] = $lang_module['transition_no_exists'];
            nv_jsonOutput($json_respon);
        }
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    $transaction['transaction_code'] = !empty($transaction['order_id']) ? sprintf('WP%010s', $transaction['id']) : sprintf('GD%010s', $transaction['id']);

    // Kiểm tra đơn hàng
    if (!empty($transaction['order_id'])) {
        $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE id=" . $transaction['order_id'];
        $order_info = $db->query($sql)->fetch();
        if (empty($order_info)) {
            if ($is_ajax) {
                $json_respon['message'] = $lang_module['paygate_error_order'];
                nv_jsonOutput($json_respon);
            }
            nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
        }
    }

    if ($is_ajax) {
        if ($transaction['transaction_status'] == 0) {
            // Tiếp tục đợi
            $json_respon['continue'] = true;
            nv_jsonOutput($json_respon);
        }
        if ($transaction['transaction_status'] == 4) {
            // Giao dịch hoàn tất
            $json_respon['success'] = true;

            if (!empty($transaction['order_id'])) {
                require_once NV_ROOTDIR . '/modules/wallet/wallet.class.php';
                $wallet = new nukeviet_wallet();

                $order_info['payurl'] = $wallet->getOrderPayUrl($order_info, false);

                // Url chuyển hướng thanh toán đơn hàng
                $checksum_str = $transaction['transaction_code'] . $transaction['money_net'] . $transaction['money_unit'] . $transaction['transaction_info'] . $transaction['tokenkey'];
                $checksum = hash('sha256', $checksum_str);

                // Tạo URL để chuyển ngay về phần complete
                $transaction['ReturnURL'] = urlRewriteWithDomain($order_info['payurl'] . '&payment=sepay&wpayportres=true', NV_MY_DOMAIN);
                $url = $transaction['ReturnURL'];
                $url .= '&code=' . $transaction['transaction_code'] . '&money=' . $transaction['money_net'] . '&unit=' . $transaction['money_unit'] . '&info=' . urlencode($transaction['transaction_info']) . '&checksum=' . $checksum;

                $json_respon['redirect'] = nv_url_rewrite($url, true);
            } else {
                // Url chuyển hướng nạp tiền
                $json_respon['redirect'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=money', true);
            }

            nv_jsonOutput($json_respon);
        }
        // Giao dịch này không còn chờ thanh toán nữa
        $json_respon['message'] = sprintf($lang_module['atm_blockstatus'], $lang_module['transaction_status' . $transaction['transaction_status']]);
        nv_jsonOutput($json_respon);
    }
    if ($transaction['transaction_status'] != 0) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    $transaction_data = unserialize($transaction['transaction_data']) ?: [];

    // Thông tin thanh toán không hợp lệ
    if (empty($transaction_data['to_account'])) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    // Xác định thông tin ngân hàng nhận
    $key_bank = -1;
    foreach ($payment_config['account_no'] as $key => $account_no) {
        if ($account_no == $transaction_data['to_account']) {
            $key_bank = $key;
            break;
        }
    }
    if ($key_bank < 0) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    // Xem thử có hỗ trợ tạo mã QR code không
    $transaction['qr_supported'] = false;
    if ($transaction['money_net'] > 0 and strlen(strval($transaction['money_net'])) <= 13) {
        if (!empty($payment_config['acq_id'][$key_bank]) and !empty($payment_config['account_name'][$key_bank]) and !empty($payment_config['bank_branch'][$key_bank])) {
            $transaction['qr_supported'] = true;
            $transaction['acq_id'] = $payment_config['acq_id'][$key_bank];
            $transaction['account_no'] = $transaction_data['to_account'];
            $transaction['account_name'] = $payment_config['account_name'][$key_bank];
            $transaction['bank_branch'] = $payment_config['bank_branch'][$key_bank];
        }
    }

    // Xử lý tạo mã QR code
    $api_banks = [];
    if ($transaction['qr_supported']) {
        // Lấy một số thông tin ngân hàng khi nạp API
        $api_banks = getVietqrBanksV2();

        if (!isset($api_banks[$transaction['acq_id']])) {
            $transaction['qr_supported'] = false;
        }
    }

    if ($transaction['qr_supported']) {
        $qr_session = $nv_Request->get_string('wallet_qrcode', 'session', '');
        $qr_session = empty($qr_session) ? [] : (json_decode($qr_session, true) ?: []);
        if (empty($qr_session) or $qr_session['id'] != $transaction['id']) {
            $body = [
                'accountNo' => $transaction['account_no'],
                'accountName' => $transaction['account_name'],
                'acqId' => $transaction['acq_id'],
                'amount' => $transaction['money_net'],
                'addInfo' => $transaction['transaction_code'],
                'format' => 'vietqr_net',
            ];
            $args = [
                'headers' => [
                    'Referer' => NV_MY_DOMAIN . nv_url_rewrite($page_url, true),
                    'x-client-id' => NV_MY_DOMAIN,
                    'x-api-key' => 'we-l0v3-v1et-qr',
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($body),
                'timeout' => 10,
                'decompress' => false,
                'sslverify' => false
            ];

            $http = new Http($global_config, NV_TEMP_DIR);
            $responsive = $http->post('https://api.vietqr.io/v1/generate', $args);

            if (!empty(Http::$error)) {
                trigger_error(Http::$error['message']);
            } elseif (!is_array($responsive)) {
                trigger_error($lang_module['atm_vietqr_error_api']);
            } elseif (empty($responsive['body'])) {
                trigger_error($lang_module['atm_vietqr_error_api']);
            } else {
                $api_body = json_decode($responsive['body'], true);
                if (!is_array($api_body) or empty($api_body['data']['qrDataURL'])) {
                    trigger_error($lang_module['atm_vietqr_error_api']);
                } else {
                    $qr_session = [
                        'id' => $transaction['id'],
                        'data' => $api_body['data']['qrDataURL'],
                    ];
                    $transaction['qr_image'] = $qr_session['data'];
                    $nv_Request->set_Session('wallet_qrcode', json_encode($qr_session));
                }
            }
        } else {
            $transaction['qr_image'] = $qr_session['data'];
        }
    }

    $transaction['type_show'] = empty($transaction['order_id']) ? $lang_module['note_pay1'] : sprintf($lang_module['paygate_tranmess'], sprintf('DH%010s', $transaction['order_id']));
    $transaction['display_money'] = display_money($transaction['money_net']);
    $transaction['show_status'] = $lang_module['transaction_status' . $transaction['transaction_status']];
    $transaction['created_time'] = nv_date('H:i d/m/Y', $transaction['created_time']);
    $transaction['ajax_url'] = str_replace('&amp;', '&', $page_url);

    $contents = nv_theme_wallet_waitsepay($transaction, $api_banks);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Chuyển đến trang nạp nếu chỉ có một cổng thanh toán
if (sizeof($url_checkout) == 1) {
    $url = current($url_checkout);
    nv_redirect_location(str_replace('&amp;', '&', $url['url']));
}

$array_replace = array(
    'SITE_NAME' => $global_config['site_name'],
    'SITE_DES' => $global_config['site_description'],
    'SITE_EMAIL' => $global_config['site_email'],
    'SITE_PHONE' => $global_config['site_phone'],
    'USER_NAME' => $user_info['username'],
    'USER_EMAIL' => $user_info['email'],
    'USER_FULLNAME' => $user_info['full_name']
);

$payport_content = nv_unhtmlspecialchars($module_config[$module_name]['payport_content']);
foreach ($array_replace as $index => $value) {
    $payport_content = str_replace('[' . $index . ']', $value, $payport_content);
}

$contents = nv_theme_wallet_main($url_checkout, $payport_content);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
