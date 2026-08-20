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

/*
 * Bảng trạng thái của đơn hàng
 * 0 => Chưa thanh toán (đơn hàng mới tạo chưa có thông tin gì về thanh toán)
 * 1 => Đang chờ xử lý
 * 2 => Đang tạm giữ (cổng thanh toán trả về trạng thái tạm giữ)
 * 3 => Thất bại (cổng thanh toán trả về trạng thái thất bại)
 * 4 => Thành công (thanh toán thành công, tiền đã được chuyển vào bên nhận)
 * 5 => Sai checksum - thanh toán không thành công tương tự như 3
 */

require_once NV_ROOTDIR . '/modules/wallet/wallet.class.php';
$wallet = new nukeviet_wallet();

// Thanh toán đơn hàng của các module kết nối
$order_id = $nv_Request->get_int('wpay', 'get', 0);
$checksum = $nv_Request->get_title('wchecksum', 'get', '');
$redirect_error = '<meta http-equiv="refresh" content="5; url=' . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA) . '"/>';
if (empty($order_id) or empty($checksum)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect_error, 404);
}
$order_info = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE id=" . $order_id)->fetch();
if (empty($order_info) or $wallet->verifyOrderChecksum($checksum, $order_info) !== true) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect_error, 404);
}
$order_info['checksum'] = $checksum;
$order_info['payurl'] = $wallet->getOrderPayUrl($order_info);

// Kiểm tra thành viên
if (!defined('NV_IS_USER')) {
    $redirect = nv_url_rewrite($order_info['payurl'], true);
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_redirect_encrypt($redirect));
}

/*
 * Đơn hàng phải ở trạng thái chưa thanh toán thì mới được thanh toán
 * Vì quy định của các cổng thanh toán là chỉ xử lý mỗi ID đơn hàng một lần.
 * Do đó các trạng thái khác tức là có chuyển về từ cổng thanh toán rồi.
 * gửi thông tin thanh toán tiếp sẽ không chấp nhận
 */
if ($order_info['paid_status'] != 0) {
    // Chuyển trả về trang xử lý kết quả của module được kết nối
    $url_back = unserialize($order_info['url_back']);
    $url_back['querystr'] = trim(str_replace('&amp;', '&', $url_back['querystr']), '&');
    $link_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $order_info['order_mod'] . '&' . NV_OP_VARIABLE . '=' . $url_back['op'];
    if (!empty($url_back['querystr'])) {
        $link_redirect .= '&' . $url_back['querystr'];
    }
    $link_redirect .= '&worderid=' . $order_info['order_id'];
    $link_redirect .= '&wchecksum=' . $wallet->getResponseChecksum($order_info, $order_info['paid_status'], $order_info['paid_time']);
    $link_redirect = nv_url_rewrite($link_redirect, true);
    nv_redirect_location($link_redirect);
}

// Xác định các cổng thanh toán
// Các cổng thanh toán này cần hỗ trợ thanh toán loại tiền tương ứng
$url_checkout = [];
foreach ($global_array_payments as $row) {
    $row['currency_support'] = explode(',', $row['currency_support']);
    // Có hỗ trợ thanh toán tùy chỉnh mới được tiếp tục, không hỗ trợ thanh toán tùy chỉnh thì chỉ dùng để nạp tiền
    if (file_exists(NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $row['payment'] . ".checkout_url.php") and !empty($row['allowedoptionalmoney'])) {
        $payment_config = unserialize(nv_base64_decode($row['config']));
        $payment_config['paymentname'] = $row['paymentname'];
        $payment_config['domain'] = $row['domain'];

        $images_button = $row['images_button'];
        $url = $order_info['payurl'] . '&amp;payment=' . $row['payment'];

        if (!empty($images_button) and file_exists(NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $images_button)) {
            $images_button = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $images_button;
        }

        $payment_type = '';
        $exchange_info = array();
        if (in_array($order_info['money_unit'], $row['currency_support'])) {
            $payment_type = 'direct';
        } elseif (!empty($module_config[$module_name]['allow_exchange_pay'])) {
            $currency_exchange = current($row['currency_support']);
            if (!empty($currency_exchange)) {
                $money_exchange = nv_wallet_tinhtoan($order_info['money_unit'], $currency_exchange, $order_info['money_amount']);
                if ($money_exchange > 0) {
                    $payment_type = 'exchange';
                    $exchange_info['total'] = $money_exchange;
                    $exchange_info['currency'] = $currency_exchange;
                }
            }
        }

        if (!empty($payment_type)) {
            $url_checkout[$row['payment']] = [
                "name" => $row['paymentname'],
                "url" => $url,
                "images_button" => $images_button,
                'data' => $row,
                'exchange_info' => $exchange_info,
                'payment_type' => $payment_type
            ];
        }
    }
}

// Xác định ví tiền
$money_info = getInfoMoney($order_info['money_unit']);
$money_info['linkpay'] = $order_info['payurl'] . '&amp;wallet=' . NV_CHECK_SESSION;

// Thanh toán bằng số dư trong ví tiền
if ($nv_Request->isset_request('wallet', 'get')) {
    $wallet_check = $nv_Request->get_title('wallet', 'get', '');
    if ($wallet_check != NV_CHECK_SESSION or $order_info['paid_status'] != 0) {
        nv_redirect_location($order_info['payurl']);
    }
    // Kiểm tra lại số tiền
    if ($order_info['money_amount'] > $money_info['moneytotalnotformat']) {
        redict_link($lang_module['paygate_wpay_notenought'], $lang_module['back'], nv_url_rewrite($order_info['payurl'], true));
    }
    // Bắt đầu thanh toán
    if (empty($order_info['order_object']) and empty($order_info['order_name'])) {
        $order_obj = $lang_module['paygate_objnone'];
    } else {
        $order_obj = $order_info['order_object'] . ' ' . $order_info['order_name'];
    }

    $message = $lang_module['paygate_title'] . " " . $order_obj;
    $transaction_id = $wallet->update($order_info['money_amount'], $order_info['money_unit'], $user_info['userid'], $message, false);
    if ($wallet->isError()) {
        redict_link($transaction_id, $lang_module['back'], nv_url_rewrite($order_info['payurl'], true));
    }
    $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET
        paid_status=4,
        paid_id=" . $db->quote(sprintf('WP%010s', $transaction_id)) . ",
        paid_time=" . NV_CURRENTTIME . "
    WHERE id=" . $order_id);
    if (!$check) {
        redict_link($lang_module['paygate_error_update'], $lang_module['back'], nv_url_rewrite($order_info['payurl'], true));
    }

    // Chuyển trả về trang xử lý kết quả của module được kết nối
    $url_back = unserialize($order_info['url_back']);
    $url_back['querystr'] = trim(str_replace('&amp;', '&', $url_back['querystr']), '&');
    $link_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $order_info['order_mod'] . '&' . NV_OP_VARIABLE . '=' . $url_back['op'];
    if (!empty($url_back['querystr'])) {
        $link_redirect .= '&' . $url_back['querystr'];
    }
    $link_redirect .= '&worderid=' . $order_info['order_id'];
    $link_redirect .= '&wchecksum=' . $wallet->getResponseChecksum($order_info, 4, NV_CURRENTTIME);
    $link_redirect = nv_url_rewrite($link_redirect, true);
    nv_redirect_location($link_redirect);
}

// Thanh toán bằng các cổng thanh toán
if ($nv_Request->isset_request('payment', 'get')) {
    $payment = $nv_Request->get_title('payment', 'get', '');
    if (!isset($url_checkout[$payment])) {
        nv_redirect_location($order_info['payurl']);
    }
    $row_payment = $url_checkout[$payment]['data'];
    $payment_config = unserialize(nv_base64_decode($row_payment['config']));
    $payment_config['paymentname'] = $row_payment['paymentname'];
    $payment_config['domain'] = $row_payment['domain'];

    $array_banks = [];
    $is_vietqr = false;

    if (in_array($payment, ['ATM', 'VietQR', 'sepay'])) {
        $payment_config['account_no'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['account_no']));
        $payment_config['account_name'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['account_name']));
        $payment_config['acq_id'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(',', $payment_config['acq_id']));
    }
    if (in_array($payment, ['ATM', 'VietQR'])) {
        // Lấy một số thông tin ngân hàng khi nạp API
        if (!empty($payment_config['acq_id']) and !empty($payment_config['account_no']) and !empty($payment_config['account_name'])) {
            $is_vietqr = true;
            $array_banks = getVietqrBanksV1();
        }
    }
    if ($payment == 'sepay') {
        $payment_config['bank_branch'] = empty($payment_config['account_no']) ? [] : array_map('trim', explode(';', $payment_config['bank_branch']));
        $array_banks = getVietqrBanksV2();
    }

    // Xử lý kết quả trả về của cổng thanh toán
    if ($nv_Request->isset_request('wpayportres', 'get')) {
        // Nếu có lỗi thì đặt vào biến này
        $error = '';

        // Dữ liệu trả về đặt vào biến này
        $responseData = [
            'ordertype' => '', // Kiểu giao dịch: pay là thanh toán các đơn hàng khác, recharge là nạp tiền vào ví
            'orderid' => '', // Kiểu text, ID của giao dịch được lưu trước vào CSDL dùng để cập nhật thanh toán
            'transaction_id' => '', // Kiểu text, ID giao dịch trên cổng thanh toán
            'transaction_status' => 0, // Kiểu số, trạng thái giao dịch quy chuẩn
            'transaction_time' => 0, // Kiểu số, thời gian giao dịch
            'transaction_data' => '' // Kiểu text, có thể là serialize array
        ];

        // Gọi file xử lý dữ liệu trả về
        require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".complete.php";

        // Nếu có lỗi thì thông báo lỗi
        if (!empty($error)) {
            redict_link($error, $lang_module['cart_back'], $order_info['payurl']);
        }

        // Hủy bỏ giao dịch
        if ($responseData['transaction_status'] < 0) {
            redict_link($lang_module['pay_user_cancel'], $lang_module['cart_back'], $order_info['payurl']);
        }

        // Lấy giao dịch đã lưu vào CSDL trước đó
        $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE id = :id");
        $stmt->bindParam(':id', $responseData['orderid'], PDO::PARAM_STR);
        $stmt->execute();
        $transaction = $stmt->fetch();
        if (empty($transaction)) {
            redict_link($lang_module['transition_no_exists'], $lang_module['cart_back'], $order_info['payurl']);
        }

        if ($transaction['order_id'] != $order_id) {
            redict_link($lang_module['paygate_error_order'], $lang_module['cart_back'], $order_info['payurl']);
        }

        // Cập nhật đơn hàng rồi quay lại
        // Module liên kết tự xử lý kết quả
        // Không cập nhật nếu cổng thanh toán VNPAY
        // Không cập nhật luôn nếu cổng thanh toán SePay vì webhook Sepay đã cập nhật
        if ($payment != 'vnpay' and $payment != 'sepay') {
            // Cập nhật lại giao dịch
            $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = ' . $responseData['transaction_status'] . ',
                transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
            WHERE id = ' . $transaction['id'];

            if (!$db->query($sql)) {
                redict_link($lang_module['payclass_error_save_transaction'], $lang_module['cart_back'], $order_info['payurl']);
            }

            // Cập nhật lại đơn hàng
            $check = $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET
                paid_status=" . $responseData['transaction_status'] . ",
                paid_id=" . $db->quote(sprintf('WP%010s', $transaction['id'])) . ",
                paid_time=" . $responseData['transaction_time'] . "
            WHERE id=" . $order_id);
            if (!$check) {
                redict_link($lang_module['paygate_error_update'], $lang_module['back'], $order_info['payurl']);
            }
        } else {
            $responseData['transaction_status'] = $order_info['paid_status'];
            $responseData['transaction_time'] = $order_info['paid_time'];
        }

        // Gửi email thông báo trạng thái giao dịch
        if (!empty($module_config[$module_name]['accountants_emails']) and (
            (!empty($row_payment['active_completed_email']) and $responseData['transaction_status'] == 4) or (!empty($row_payment['active_incomplete_email']) and $responseData['transaction_status'] != 4)
        )) {
            $accountants_emails = array_filter(array_unique(array_map("trim", explode(',', $module_config[$module_name]['accountants_emails']))));

            $email_order_code = empty($transaction['order_id']) ? sprintf('GD%010s', $transaction['id']) : sprintf('WP%010s', $transaction['id']);
            $email_created_time = nv_date('H:i d/m/Y', $transaction['created_time']);
            $email_customer_name = $lang_module['email_notice_visitor'];
            if (!empty($transaction['customer_id'])) {
                $customer_db = $db->query("SELECT username, first_name, last_name FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=" . $transaction['customer_id'])->fetch();
                if (!empty($customer_db)) {
                    $email_customer_name = nv_show_name_user($customer_db['first_name'], $customer_db['last_name'], $customer_db['username']) . ' (' . $customer_db['username'] . ')';
                }
            }
            $email_money = get_display_money($transaction['money_net']) . ' ' . $transaction['money_unit'];
            $email_status = isset($global_array_transaction_status[$responseData['transaction_status']]) ? $global_array_transaction_status[$responseData['transaction_status']] : 'N/A';
            $email_url_admin = NV_MY_DOMAIN . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=viewtransaction&amp;id=" . $transaction['id'];

            $messages = sprintf($lang_module['email_notice_transaction1'], $email_order_code, $email_created_time, $email_customer_name, $email_money, $email_status, $transaction['customer_name'], $transaction['customer_email'], $transaction['customer_phone'], $transaction['customer_address'], $transaction['customer_info'], $payment, $email_url_admin, $email_url_admin);
            foreach ($accountants_emails as $email) {
                nv_sendmail([$global_config['site_email'], $global_config['site_name']], $email, $lang_module['email_notice_transaction0'], $messages);
            }
        }

        // Chuyển trả về trang xử lý kết quả của module được kết nối
        $url_back = unserialize($order_info['url_back']);
        $url_back['querystr'] = trim(str_replace('&amp;', '&', $url_back['querystr']), '&');
        $link_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $order_info['order_mod'] . '&' . NV_OP_VARIABLE . '=' . $url_back['op'];
        if (!empty($url_back['querystr'])) {
            $link_redirect .= '&' . $url_back['querystr'];
        }
        $link_redirect .= '&worderid=' . $order_info['order_id'];
        $link_redirect .= '&wchecksum=' . $wallet->getResponseChecksum($order_info, $responseData['transaction_status'], $responseData['transaction_time']);
        $link_redirect = nv_url_rewrite($link_redirect, true);

        // Đối với cổng thanh toán ATM và manual thì hiển thị thông báo trước khi chuyển về module kết nối
        if ($payment == 'manual' or $payment == 'ATM' or $payment == 'VietQR') {
            redict_link($payment_config['completemessage'], $lang_module['cart_back_pay'], $link_redirect);
        }

        // Đối với cổng thanh toán khác thì chuyển luôn về module kết nối
        nv_redirect_location($link_redirect);
    }

    // Lưu mới phiên thanh toán
    $transaction = [];
    $transaction_info = sprintf($lang_module['paygate_tranmess'], sprintf('DH%010s', $order_info['id']));
    // Tạo ngẫu nhiên một khóa xem như là Private key để tính checksum
    $tokenkey = md5($global_config['sitekey'] . $user_info['userid'] . NV_CURRENTTIME . $order_info['money_amount'] . $order_info['money_unit'] . $payment . nv_genpass());

    // Xác định số tiền và phí
    if ($url_checkout[$payment]['payment_type'] == 'direct') {
        $pay_total = $order_info['money_amount'];
        $pay_money = $order_info['money_unit'];
    } else {
        $pay_total = $url_checkout[$payment]['exchange_info']['total'];
        $pay_money = $url_checkout[$payment]['exchange_info']['currency'];
    }
    $money_net = $money_revenue = get_db_money($pay_total, $pay_money);
    $money_discount = 0;
    $money_total = 0; // Thanh toán hóa đơn thì không thay đổi gì vào tài khoản

    $money_discount = get_db_money($row_payment['discount_transaction'] + (($row_payment['discount'] * $money_net) / 100), $pay_money);
    $money_revenue = get_db_money($money_net - $money_discount, $pay_money);

    // Đối với cổng thanh toán ATM, VietQR tại đây cần lấy thông tin của khách
    $post = [];
    $post['atm_sendbank'] = '';
    $post['atm_fracc'] = '';
    $post['atm_time'] = '';
    $post['atm_toacc'] = '';
    $post['atm_heading'] = '';
    $post['atm_recvbank'] = '';
    $post['atm_filedepute'] = ''; // Tên file hiện tại
    $post['atm_filedepute_key'] = ''; // Khóa file hiện tại
    $post['atm_filebill'] = ''; // Tên file hiện tại
    $post['atm_filebill_key'] = ''; // Khóa file hiện tại
    $post['atm_acq'] = -1; // Offset key của ngân hàng nhận
    $post['vietqr_screenshots'] = ''; // Tên ảnh chụp màn hình hiện tại
    $post['vietqr_screenshots_key'] = ''; // Khóa ảnh chụp màn hình hiện tại
    $post['to_account'] = ''; // Sepay số tài khoản nhận

    // Quy định tiếng Việt không dấu, tối đa 25 ký tự. Không ký tự đặc biệt
    $post['atm_transaction_info'] = ucfirst(str_replace('-', ' ', change_alias(sprintf($lang_module['paygate_tranmess1'], sprintf('DH%010s', $order_id)))));
    $post['transaction_data'] = '';

    // Gọi API lấy mã VietQR
    if ($is_vietqr and $nv_Request->get_title('getvietqrcode', 'post', '') === NV_CHECK_SESSION) {
        $respon = [
            'message' => 'error!',
            'success' => 0,
            'img' => []
        ];

        $acq = $nv_Request->get_int('acq', 'post', -1);

        if (!$is_vietqr or !isset($payment_config['acq_id'][$acq])) {
            $respon['message'] = $lang_module['atm_vietqr_error_acq'];
        } else {
            $body = [
                'accountNo' => $payment_config['account_no'][$acq],
                'accountName' => $payment_config['account_name'][$acq],
                'acqId' => $payment_config['acq_id'][$acq],
                'amount' => $money_net,
                'addInfo' => $post['atm_transaction_info'],
                'format' => 'vietqr_net',
            ];
            if (empty($body['addInfo'])) {
                // Hiện API truyền addInfo empty vào bị treo do đó empty thì bỏ field này
                unset($body['addInfo']);
            }
            $args = [
                'headers' => [
                    'Referer' => $client_info['selfurl'],
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
                $respon['message'] = Http::$error['message'];
            } elseif (!is_array($responsive)) {
                $respon['message'] = $lang_module['atm_vietqr_error_api'];
            } elseif (empty($responsive['body'])) {
                $respon['message'] = $lang_module['atm_vietqr_error_api'];
            } else {
                $api_body = json_decode($responsive['body'], true);
                if (!is_array($api_body) or empty($api_body['data']['qrDataURL'])) {
                    $respon['message'] = $lang_module['atm_vietqr_error_api'];
                } else {
                    $respon['success'] = 1;
                    $respon['img'] = $api_body['data']['qrDataURL'];
                }
            }
        }

        nv_jsonOutput($respon);
    }

    // Xử lý riêng cổng ATM và VietQR
    if ($payment == 'ATM' or $payment == 'VietQR') {
        $isSubmit = false;
        $error = $atm_error = '';

        if ($nv_Request->isset_request('fsubmit', 'post')) {
            $isSubmit = true;

            unset($fcode);
            if ($module_captcha == 'recaptcha') {
                // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
                $fcode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
            } elseif ($module_captcha == 'captcha') {
                // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
                $fcode = $nv_Request->get_title('fcode', 'post', '');
            }

            define('NV_IS_' . strtoupper($payment) . '_FORM', true);
            require NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.form.php';

            if (isset($fcode) and !nv_capcha_txt($fcode, $module_captcha)) {
                $error = ($module_captcha == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'];
            } elseif (!empty($atm_error)) {
                $error = $atm_error;
            } else {
                // Xử lý trước khi lưu CSDL
                require NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.presave.php';
            }
        }

        if (!$isSubmit or !empty($error)) {
            $contents = nv_theme_wallet_atm_pay($order_info, $row_payment, $post, $error);

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_site_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }

    // Xử lý riêng cổng SePay
    if ($payment == 'sepay') {
        $isSubmit = false;
        $error = $sepay_error = '';

        if ($nv_Request->isset_request('fsubmit', 'post')) {
            $isSubmit = true;

            unset($fcode);
            if ($module_captcha == 'recaptcha') {
                // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
                $fcode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
            } elseif ($module_captcha == 'captcha') {
                // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
                $fcode = $nv_Request->get_title('fcode', 'post', '');
            }

            define('NV_IS_SEPAY_FORM', true);
            require NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.form.php';

            if (isset($fcode) and !nv_capcha_txt($fcode, $module_captcha)) {
                $error = ($module_captcha == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'];
            } elseif (!empty($sepay_error)) {
                $error = $sepay_error;
            } else {
                // Xử lý trước khi lưu CSDL
                require NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.presave.php';
            }
        }

        if (!$isSubmit or !empty($error)) {
            $contents = nv_theme_wallet_sepay_pay($order_info, $row_payment, $post, $error);

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_site_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    }

    $transaction['id'] = $db->insert_id("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (
        created_time, status, money_unit, money_total, money_net, money_discount, money_revenue, userid, adminid, order_id, customer_id, customer_name,
        customer_email, customer_phone, customer_address, customer_info, transaction_id, transaction_type, transaction_status, transaction_time,
        transaction_info, transaction_data, payment, provider, tokenkey
    ) VALUES (
        " . NV_CURRENTTIME . ", -1, " . $db->quote($pay_money) . ", " . $money_total . ", " . $money_net . ", " . $money_discount . ",
        " . $money_revenue . ", " . $user_info['userid'] . ", 0, " . $order_info['id'] . ", " . $user_info['userid'] . ", " . $db->quote($user_info['full_name']) . ",
        " . $db->quote($user_info['email']) . ", '', '', '', '', -1, 0, 0,
        " . $db->quote($transaction_info) . ", " . $db->quote($post['transaction_data']) . ", " . $db->quote($payment) . ",
        '', " . $db->quote($tokenkey) . "
    )", 'id');

    if (empty($transaction['id'])) {
        redict_link($lang_module['paygate_error_savetransaction'], $lang_module['cart_back'], $order_info['payurl']);
    }

    // Tạo dữ liệu cổng thanh toán
    $post = [];
    $post['transaction_code'] = sprintf('WP%010s', $transaction['id']);
    $post['transaction_info'] = sprintf($lang_module['paygate_tranmess_send'], $post['transaction_code'], NV_SERVER_NAME);
    $post['money_net'] = $money_net;
    $post['money_unit'] = $pay_money;
    $post['customer_phone'] = '';
    $post['customer_email'] = $user_info['email'];
    $post['ReturnURL'] = NV_MY_DOMAIN . nv_url_rewrite($order_info['payurl'] . '&payment=' . $payment . '&wpayportres=true', true);
    $post['tokenkey'] = $tokenkey;

    $url = '';
    $error = '';
    require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".checkout_url.php";

    // Nếu có lỗi thì thông báo lỗi
    if (!empty($error)) {
        redict_link($error, $lang_module['cart_back'], $order_info['payurl']);
    }

    if (!empty($url)) {
        nv_redirect_location($url);
    }
    nv_redirect_location($order_info['payurl']);
}

if (empty($order_info['order_object']) and empty($order_info['order_name'])) {
    $order_info['title'] = $lang_module['paygate_objnone'];
} else {
    $order_info['title'] = $lang_module['paygate_title'] . ' ' . $order_info['order_object'] . ' ' . $order_info['order_name'];
}
$page_title = $order_info['title'];
$array_mod_title[] = array(
    'title' => $page_title
);

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$contents = nv_theme_wallet_pay($url_checkout, $module_config[$module_name]['payport_content'], $order_info, $money_info);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
