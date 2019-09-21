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

$page_title = $lang_module['titleSmsNap'];
$payment = isset($array_op[1]) ? $array_op[1] : "";

if (isset($global_array_payments[$payment])) {
    if ($payment == "sms") {
        // Nạp qua SMS, hiện đã dừng hoạt động
        if (!defined('NV_IS_USER')) {
            $linkdirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_redirect_encrypt($client_info['selfurl']);
            $content = "<br /><img border=\"0\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/images/load_bar.gif\"><br /><br />\n";
            $content .= $lang_module['payment_login'];
            $content .= "<meta http-equiv=\"refresh\" content=\"5;url=" . $linkdirect . "\" />";

            nv_info_die($module_info['custom_title'], $lang_module['payment_login_wait'], $content);
        }
        if ($module_config[$module_name]['allow_smsNap'] == 1) {
            $smsConfig_keyword = $smsConfig_port = $smsConfig_prefix = "";
            $temp = explode(" ", $module_config[$module_name]['smsConfigNap']);
            if (count($temp) == 2) {
                $smsConfig_keyword = $temp[0];
                $smsConfig_port = $temp[1];
            } elseif (count($temp) == 3) {
                $smsConfig_keyword = $temp[0];
                $smsConfig_prefix = $temp[1];
                $smsConfig_port = $temp[2];
            }
            $contents = nv_theme_sms($smsConfig_keyword, $smsConfig_port, $smsConfig_prefix);
        } else {
            $contents = "<center>" . $lang_module['nosms'] . "</center>";
        }
    } else {
        // Nạp qua các cổng thanh toán, cần yêu cầu đăng nhập thành viên
        $row_payment = $global_array_payments[$payment];
        $row_payment['currency_support'] = explode(',', $row_payment['currency_support']);
        $payment_config = unserialize(nv_base64_decode($row_payment['config']));
        $payment_config['paymentname'] = $row_payment['paymentname'];
        $payment_config['domain'] = $row_payment['domain'];
        $post = array();

        if (!defined('NV_IS_USER')) {
            $linkdirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_redirect_encrypt($client_info['selfurl']);
            $contents = nv_theme_alert($lang_module['no_account'], $lang_module['payment_login'], 'info', $linkdirect, $lang_module['payment_login_wait']);
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_site_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        }

        /**
         * Xác định loại tiền mà cổng này hỗ trợ nạp
         * Nếu cổng này không cho nạp tùy ý thì chỉ lấy ra những loại tiền có cấu hình mốc nạp
         */
        $array_money_unit = [];
        foreach ($row_payment['currency_support'] as $currency) {
            if (isset($global_array_money_sys[$currency]) and (!empty($row_payment['allowedoptionalmoney']) or !empty($module_config[$module_name]['minimum_amount'][$currency]))) {
                $array_money_unit[$currency] = $currency;
            }
        }
        if (empty($array_money_unit)) {
            $redict_link = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true);
            redict_link($lang_module['recharge_error_message'], $lang_module['recharge_error_message_back'], $redict_link);
        }

        if ($payment == 'epay') {
            // Nạp qua Ebay xử lý riêng
            require_once (NV_ROOTDIR . "/modules/" . $module_file . "/payment/epay.complete.php");
        } elseif ($payment == 'gamebank') {
            // Nạp qua Gamebank xử lý riêng
            require_once (NV_ROOTDIR . "/modules/" . $module_file . "/payment/gamebank.checkout_url.php");
        } elseif ($payment == 'vnptepay') {
            // Nạp qua VNPT Epay xử lý riêng
            require_once (NV_ROOTDIR . "/modules/" . $module_file . "/payment/vnptepay.checkout_url.php");
        } else {
            $error = "";
            $checkss = $nv_Request->get_title('checkss', 'post', '');

            if ($checkss == md5($payment . $global_config['sitekey'] . session_id())) {
                $post['customer_name'] = $nv_Request->get_title('customer_name', 'post', '');
                $post['customer_email'] = $nv_Request->get_title('customer_email', 'post', '');
                $post['customer_phone'] = $nv_Request->get_title('customer_phone', 'post', '');
                $post['customer_address'] = $nv_Request->get_title('customer_address', 'post', '');
                $post['customer_info'] = "";

                $post['transaction_info'] = $nv_Request->get_title('transaction_info', 'post', '');
                $post['check_term'] = $nv_Request->get_int('check_term', 'post, get', 0);

                if ($global_config['captcha_type'] == 2) {
                    $fcode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
                } else {
                    $fcode = $nv_Request->get_title('capchar', 'post', '');
                }

                $post['money_amount'] = $nv_Request->get_title('money_amount', 'post', '');
                $post['money_other'] = $nv_Request->get_title('money_other', 'post', '');

                if (sizeof($array_money_unit) == 1) {
                    $post['money_unit'] = current($array_money_unit);
                } else {
                    $post['money_unit'] = $nv_Request->get_title('money_unit', 'post', '');
                    if (!isset($array_money_unit[$post['money_unit']])) {
                        $post['money_unit'] = '';
                    }
                }

                $money_amount = floatval(str_replace(array(" ", ","), "", $post['money_amount']));
                if ($post['money_unit'] == 'VND') {
                    $money_amount = intval($money_amount);
                }
                $money_other = floatval(str_replace(array(" ", ","), "", $post['money_other']));
                if ($post['money_unit'] == 'VND') {
                    $money_other = intval($money_other);
                }
                $post['money_amount'] = $money_amount;
                $post['money_other'] = $money_other;

                $money = empty($post['money_amount']) ? $post['money_other'] : $post['money_amount'];

                $check_valid_email = nv_check_valid_email($post['customer_email']);

                if (empty($post['customer_name'])) {
                    $post['customer_name'] = $user_info['username'];
                }

                // Kiểm tra mức tiền nhỏ nhất
                $minimum_amount = !empty($module_config[$module_name]['minimum_amount'][$post['money_unit']]) ? explode(',', $module_config[$module_name]['minimum_amount'][$post['money_unit']]) : array();
                $minimum_amount = empty($minimum_amount) ? 0 : $minimum_amount[0];

                // Xử lý form và lỗi đối với cổng thanh toán ATM
                $atm_error = '';
                if ($payment == 'ATM') {
                    define('NV_IS_ATM_FORM', true);
                    require NV_ROOTDIR . '/modules/' . $module_file . '/payment/ATM.form.php';
                } else {
                    $post['transaction_data'] = '';
                }

                if (!empty($post['customer_email']) and !empty($check_valid_email)) {
                    $error = $check_valid_email;
                } elseif (empty($post['money_unit'])) {
                    $error = $lang_module['payclass_error_money_unit'];
                } elseif ($money <= 0 or ($minimum_amount > 0 and $money < $minimum_amount)) {
                    if ($minimum_amount > 0) {
                        $error = sprintf($lang_module['error_money_recharge1'], get_display_money($minimum_amount) . ' ' . $post['money_unit']);
                    } else {
                        $error = $lang_module['error_money_recharge'];
                    }
                } elseif (!empty($atm_error)) {
                    $error = $atm_error;
                } elseif ($post['check_term'] != 1 and !empty($row_payment['term'])) {
                    $error = $lang_module['error_check_term'];
                } elseif (!nv_capcha_txt($fcode)) {
                    $error = ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']);
                } else {
                    $money = get_db_money($money, $post['money_unit']);
                    $post['customer_id'] = $post['userid'] = $user_info['userid'];
                    $post['money_total'] = $money; // Số tiền sẽ cộng vào tài khoản thành viên
                    $post['money_net'] = $money; // Số tiền thành viên thực hiện nạp
                    $post['money_discount'] = 0; // Phí chi trả cho cổng thanh toán, dịch vụ....
                    $post['money_revenue'] = $money; // Lợi nhuận thu được

                    /**
                     * Phí giao dịch
                     */
                    $post['money_discount'] = get_db_money($row_payment['discount_transaction'] + (($row_payment['discount_transaction'] * $post['money_net']) / 100), $post['money_unit']);
                    $post['money_revenue'] = get_db_money($post['money_net'] - $post['money_discount'], $post['money_unit']);
                    if (isset($module_config[$module_name]['recharge_rate'][$post['money_unit']])) {
                        $post['money_total'] = $post['money_total'] * $module_config[$module_name]['recharge_rate'][$post['money_unit']]['r'] / $module_config[$module_name]['recharge_rate'][$post['money_unit']]['s'];
                        $post['money_total'] = get_db_money($post['money_total'], $post['money_unit']);
                    }

                    // Tạo ngẫu nhiên một khóa xem như là Private key để tính checksum
                    $post['tokenkey'] = md5($global_config['sitekey'] . $post['userid'] . NV_CURRENTTIME . $post['customer_id'] . $post['money_net'] . $post['money_unit'] . $payment . nv_genpass());

                    // Thông tin giao dịch mặc định nếu khách không nhập
                    if (empty($post['transaction_info'])) {
                        $post['transaction_info'] = sprintf($lang_module['note_pay_gate'], $payment);
                    }

                    // Xử lý trước khi lưu CSDL
                    if ($payment == 'ATM') {
                        require NV_ROOTDIR . '/modules/' . $module_file . '/payment/ATM.presave.php';
                    }

                    // Lưu vào giao dịch (Giao dịch này là chưa thanh toán, sau này thanh toán nếu thành công hay thất bại sẽ cập nhật lại chỗ này)
                    $post['id'] = $db->insert_id("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (
                        created_time, status, money_unit, money_total, money_net, money_discount, money_revenue, userid, adminid, customer_id, customer_name,
                        customer_email, customer_phone, customer_address, customer_info, transaction_id, transaction_type, transaction_status, transaction_time,
                        transaction_info, transaction_data, payment, provider, tokenkey
                    ) VALUES (
                        " . NV_CURRENTTIME . ", 1, " . $db->quote($post['money_unit']) . ", " . $post['money_total'] . ", " . $post['money_net'] . ", " . $post['money_discount'] . ",
                        " . $post['money_revenue'] . ", " . $post['userid'] . ", 0, " . $post['customer_id'] . ", " . $db->quote($post['customer_name']) . ",
                        " . $db->quote($post['customer_email']) . ", " . $db->quote($post['customer_phone']) . ", " . $db->quote($post['customer_address']) . ",
                        " . $db->quote($post['customer_info']) . ", '', -1, 0, 0, " . $db->quote($post['transaction_info']) . ", " . $db->quote($post['transaction_data']) . ",
                        " . $db->quote($payment) . ", '', " . $db->quote($post['tokenkey']) . "
                    )", 'id');

                    if (empty($post['id'])) {
                        $error = $lang_module['payclass_error_save_transaction'];
                    } else {
                        // Noi dung gui cho cong thanh toan
                        $post['transaction_code'] = vsprintf('GD%010s', $post['id']);
                        $post['transaction_info'] = sprintf($lang_module['transaction_info'], $post['transaction_code'], NV_SERVER_NAME);
                        $post['ReturnURL'] = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=complete&payment=" . $payment, true);

                        $url = '';
                        $error = '';
                        require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".checkout_url.php";

                        // Nếu có lỗi thì thông báo lỗi
                        if (!empty($error)) {
                            redict_link($error, $lang_module['cart_back'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
                        }

                        if (!empty($url)) {
                            Header("Location: " . $url);
                            die();
                        } else {
                            nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
                        }
                    }
                }
            } else {
                $post['customer_name'] = $user_info['full_name'];
                $post['customer_email'] = $user_info['email'];
                $post['customer_phone'] = '';
                $post['customer_address'] = '';
                $post['money_amount'] = 0;
                $post['money_other'] = 0;
                $post['money_unit'] = '';
                $post['check_term'] = 0;
                $post['transaction_info'] = nv_substr($nv_Request->get_title('info', 'get', ''), 0, 250);

                /*
                 * Xử lý khi có số tiền nạp vào từ URL dạng ?amount=100000-VND
                 * Check xem cổng thanh toán nạp được loại tiền đó không
                 * Check xem có cấu hình giá trị nhỏ nhất nạp không
                 */
                $pay_amount = $nv_Request->get_title('amount', 'get', '');
                $pay_money = '';
                if (preg_match('/^([0-9\.]+)\-([A-Z]{3})$/', $pay_amount, $m)) {
                    if (!isset($global_array_money_sys[$m[2]])) {
                        $pay_amount = 0;
                    } else {
                        $pay_money = $m[2];
                        $pay_amount = $m[1];
                    }
                } else {
                    $pay_amount = 0;
                }
                if (!empty($pay_amount) and in_array($pay_money, $row_payment['currency_support'])) {
                    $post['money_unit'] = $pay_money;
                    $list_money = array_filter(explode(',', $module_config[$module_name]['minimum_amount'][$post['money_unit']]));
                    if (in_array($pay_amount, $list_money)) {
                        $post['money_amount'] = $pay_amount;
                    } elseif (empty($list_money) or min($list_money) <= $pay_amount) {
                        $post['money_other'] = $pay_amount;
                    }
                }

                if (empty($post['money_amount']) and empty($post['money_other'])) {
                    reset($array_money_unit);
                    $post['money_unit'] = current($array_money_unit);
                    if (!empty($module_config[$module_name]['minimum_amount'][$post['money_unit']])) {
                        $list_money = explode(',', $module_config[$module_name]['minimum_amount'][$post['money_unit']]);
                        $post['money_amount'] = $list_money[0];
                    }
                }

                // Thêm một số dữ liệu của kiểu thanh toán ATM
                if ($payment == 'ATM') {
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
                }
            }

            $post['checkss'] = md5($payment . $global_config['sitekey'] . session_id());
            $post['error'] = $error;

            if ($post['money_amount'] <= 0) {
                $post['money_amount'] = '';
            }
            if ($post['money_other'] <= 0) {
                $post['money_other'] = '';
            }

            $page_title = $module_info['site_title'];
            $key_words = $module_info['keywords'];

            $contents = nv_theme_wallet_recharge($row_payment, $post, $array_money_unit);
        }
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
