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

/**
 * redict_link()
 *
 * @param mixed $lang_view
 * @param mixed $lang_back
 * @param mixed $nv_redirect
 * @return
 */
function redict_link($lang_view, $lang_back, $nv_redirect)
{
    $nv_redirect = nv_url_rewrite($nv_redirect, true);
    $contents = "<div class=\"text-center alert alert-info\">";
    $contents .= $lang_view . "<br /><br />\n";
    $contents .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/images/load_bar.gif\"><br /><br />\n";
    $contents .= "<a href=\"" . $nv_redirect . "\">" . $lang_back . "</a>";
    $contents .= "</div>";
    $contents .= "<meta http-equiv=\"refresh\" content=\"6;url=" . $nv_redirect . "\" />";
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * nv_theme_wallet_main()
 *
 * @param mixed $url_checkout
 * @param mixed $payport_content
 * @return
 */
function nv_theme_wallet_main($url_checkout, $payport_content)
{
    global $global_config, $module_name, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('module_file', $module_info['module_theme']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $module_info['template']);

    if (!empty($payport_content)) {
        $xtpl->assign('PAYPORT_CONTENT', $payport_content);
        $xtpl->parse('main.payport_content');
    }

    $flag = false;
    if ($module_config[$module_name]['allow_smsNap'] == 1) {
        $xtpl->assign('URLNAP', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=pay/sms");
        $xtpl->parse('main.payment.smsNap');
        $flag = true;
    }

    if (!empty($url_checkout)) {
        $loop_i = 0;
        foreach ($url_checkout as $value) {
            $loop_i++;
            $xtpl->assign('DATA_PAYMENT', $value);
            if ($loop_i % 2 == 0) {
                $xtpl->parse('main.payment.paymentloop.clear_sm');
            }
            if ($loop_i % 3 == 0) {
                $xtpl->parse('main.payment.paymentloop.clear_md');
            }
            $xtpl->parse('main.payment.paymentloop');

            if (!empty($value['guide'])) {
                $xtpl->parse('main.payment.paymentguideloop.guide');
            }
            $xtpl->parse('main.payment.paymentguideloop');
        }

        $flag = true;
    }
    if ($flag) {
        $xtpl->parse('main.payment');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_wallet_recharge()
 *
 * @param mixed $row_payment
 * @param mixed $post
 * @param mixed $array_money_unit
 * @return
 */
function nv_theme_wallet_recharge($row_payment, $post, $array_money_unit)
{
    global $global_config, $module_name, $lang_module, $lang_global, $module_config, $module_info, $op, $module_captcha;

    $xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . "index.php?scaptcha=captcha&t=" . NV_CURRENTTIME);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
    $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
    $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . "/images/refresh.png");
    $xtpl->assign('ROW_PAYMENT', $row_payment);
    $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/" . $row_payment['payment']);

    $isOnlyOneMoneyUnit = false;
    $sizeMoneyUnit = sizeof($array_money_unit);

    if ($sizeMoneyUnit == 1) {
        $xtpl->assign('MONEY_UNIT', $post['money_unit']);
        $xtpl->parse('main.money_unit_text');
    } else {
        foreach ($array_money_unit as $money_unit) {
            $xtpl->assign('MONEY_UNIT', $money_unit);
            $xtpl->assign('MONEY_UNIT_SELECTED', $money_unit == $post['money_unit'] ? ' selected="selected"' : '');
            $xtpl->parse('main.money_unit_sel.loop');
        }
        $xtpl->parse('main.money_unit_sel');
    }

    // Xuất các mức tiền nạp của loại tiền tệ đang chọn
    $array_amount = $module_config[$module_name]['minimum_amount'][$post['money_unit']];
    $array_amount = array_filter(explode(',', $array_amount));
    if (!empty($array_amount)) {
        $xtpl->assign('DISPLAY_MINIMUM_AMOUNT', '');
        $xtpl->assign('MINIMUM_AMOUNT', get_display_money($array_amount[0]));
        foreach ($array_amount as $amount) {
            $select_amount = array(
                'key' => $amount,
                'title' => get_display_money($amount),
                'selected' => $post['money_amount'] == $amount ? ' selected="selected"' : ''
            );
            $xtpl->assign('SELECT_AMOUNT', $select_amount);
            $xtpl->parse('main.select_amount.loop');
        }
        if (!empty($row_payment['allowedoptionalmoney'])) {
            if (empty($post['money_amount'])) {
                $xtpl->assign('SELECT_AMOUNT_OTHER', ' selected="selected"');
            } else {
                $xtpl->assign('SELECT_AMOUNT_OTHER', '');
            }
            $xtpl->parse('main.select_amount.other');
        }
        $xtpl->parse('main.select_amount');
    } else {
        $xtpl->assign('DISPLAY_MINIMUM_AMOUNT', ' style="display: none;"');
        $xtpl->assign('MINIMUM_AMOUNT', '');
        // Nếu không cấu hình mức tiền tệ thì xuất input nhập tiền
        $xtpl->parse('main.input_amount');
    }

    // Nếu có nhiều hơn một loại tiền thì xuất ra HTML tạm các select mức tiền của cổng đó
    if ($sizeMoneyUnit > 1) {
        foreach ($array_money_unit as $money_unit) {
            $xtpl->assign('TMP_MONEY_UNIT', $money_unit);

            $array_amount = $module_config[$module_name]['minimum_amount'][$money_unit];
            $array_amount = array_filter(explode(',', $array_amount));
            if (!empty($array_amount)) {
                $xtpl->assign('TMP_MINIMUM_AMOUNT', get_display_money($array_amount[0]));
                foreach ($array_amount as $amount) {
                    $select_amount = array(
                        'key' => $amount,
                        'title' => get_display_money($amount),
                        'selected' => $post['money_amount'] == $amount ? ' selected="selected"' : ''
                    );
                    $xtpl->assign('SELECT_AMOUNT', $select_amount);
                    $xtpl->parse('main.tmp_area.unit.select.loop');
                }
                if (!empty($row_payment['allowedoptionalmoney'])) {
                    $xtpl->parse('main.tmp_area.unit.select.other');
                }
                $xtpl->parse('main.tmp_area.unit.select');
            } else {
                $xtpl->assign('TMP_MINIMUM_AMOUNT', 'false');
                // Nếu không cấu hình mức tiền tệ thì xuất input nhập tiền
                $xtpl->parse('main.tmp_area.unit.input');
            }

            $xtpl->parse('main.tmp_area.unit');
        }
        $xtpl->parse('main.tmp_area');
    }

    // Hiển thị hoặc ẩn ô nhập số tiền khác
    if (!empty($row_payment['allowedoptionalmoney']) and empty($post['money_amount'])) {
        $xtpl->assign('SHOWCUSTOMMONEYAMOUNT', '');
    } else {
        $xtpl->assign('SHOWCUSTOMMONEYAMOUNT', ' style="display: none;"');
    }

    $post['check_term'] = empty($post['check_term']) ? '' : ' checked="checked"';

    $xtpl->assign('DATA', $post);

    // Điều khoản thanh toán
    if (!empty($row_payment['term'])) {
        $xtpl->parse('main.term');
    }

    // Xuất riêng đối với cổng ATM
    if ($row_payment['payment'] == 'ATM') {
        if (!empty($post['atm_filedepute_key'])) {
            $xtpl->assign('SHOW_ATM_FILEDEPUTE', ' class="hidden"');
            $xtpl->parse('main.atm.atm_filedepute');
        } else {
            $xtpl->assign('SHOW_ATM_FILEDEPUTE', '');
        }

        if (!empty($post['atm_filebill_key'])) {
            $xtpl->assign('SHOW_ATM_FILEBILL', ' class="hidden"');
            $xtpl->parse('main.atm.atm_filebill');
        } else {
            $xtpl->assign('SHOW_ATM_FILEBILL', '');
        }

        $xtpl->parse('main.atm');
        $xtpl->parse('main.atm_form');
    }

    // Xác định có áp dụng reCaptcha hay không
    $reCaptchaPass = (!empty($global_config['recaptcha_sitekey']) and !empty($global_config['recaptcha_secretkey']) and ($global_config['recaptcha_ver'] == 2 or $global_config['recaptcha_ver'] == 3));

    // Nếu dùng reCaptcha v3
    if ($module_captcha == 'recaptcha' and $reCaptchaPass and $global_config['recaptcha_ver'] == 3) {
        $xtpl->parse('main.recaptcha3');
    }
    // Nếu dùng reCaptcha v2
    elseif ($module_captcha == 'recaptcha' and $reCaptchaPass and $global_config['recaptcha_ver'] == 2) {
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->parse('main.recaptcha');
    }

    if (!empty($post['error'])) {
        $xtpl->parse('main.error');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_wallet_acountuser()
 *
 * @param mixed $arr_money_user
 * @return
 */
function nv_wallet_acountuser($arr_money_user)
{
    global $global_config, $module_name, $lang_module, $lang_global, $module_config, $module_info, $op;

    $xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);

    for ($i = 0; $i < count($arr_money_user); $i++) {
        $arr_money_user_i = $arr_money_user[$i]['detail'];
        $xtpl->assign('ROW', $arr_money_user_i);
        $xtpl->parse('main.loop');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_wallet_money_sys()
 *
 * @param mixed $arr_money_sys
 * @return
 */
function nv_wallet_money_sys($arr_money_sys)
{
    global $global_config, $module_name, $lang_module, $lang_global, $module_config, $module_info, $op;

    $xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('URL_EXCHANGE_BACK', nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=money", true));

    foreach ($arr_money_sys as $arr_money_sys_i) {
        $xtpl->assign('money1', $arr_money_sys_i['code']);
        $xtpl->parse('main.loopmoney1');
        $xtpl->assign('money2', $arr_money_sys_i['code']);
        $xtpl->parse('main.loopmoney2');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_wallet_history_exchange()
 *
 * @param mixed $array
 * @param mixed $generate_page
 * @param mixed $page
 * @return
 */
function nv_wallet_history_exchange($array, $generate_page, $page, $per_page)
{
    global $module_name, $lang_module, $lang_global, $module_info, $op, $global_array_transaction_status;

    $xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);

    $i = ($page - 1) * $per_page;
    foreach ($array as $row) {
        $i++;
        $xtpl->assign('STT', $i);

        $row['created_time'] = date("d/m/Y H:i", $row['created_time']);
        $row['money_total'] = get_display_money($row['money_total']);
        $row['money_net'] = get_display_money($row['money_net']);
        $row['status'] = empty($row['order_id']) ? ($row['status'] == 1 ? '+' : '-') : '';
        $row['transaction_status'] = isset($global_array_transaction_status[$row['transaction_status']]) ? $global_array_transaction_status[$row['transaction_status']] : 'N/A';

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');

    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_wallet_pay()
 *
 * @param mixed $row_payment
 * @return
 */
function nv_theme_wallet_pay_gamebank($row_payment, $post, $error)
{
    global $global_config, $module_name, $lang_module, $lang_global, $module_config, $module_info, $op;

    if (empty($row_payment['bodytext'])) {
        $lang_module['note_pay'] = sprintf($lang_module['note_pay'], $row_payment['domain']);
    } else {
        $lang_module['note_pay'] = $row_payment['bodytext'];
    }
    $xtpl = new XTemplate("gamebank.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . "index.php?scaptcha=captcha&t=" . NV_CURRENTTIME);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
    $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
    $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . "/images/refresh.png");
    $xtpl->assign('NV_GFX_NUM', NV_GFX_NUM);
    $xtpl->assign('DATA', $post);
    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_sms()
 *
 * @param mixed $smsConfig_keyword
 * @param mixed $smsConfig_port
 * @param mixed $smsConfig_prefix
 * @return
 */
function nv_theme_sms($smsConfig_keyword, $smsConfig_port, $smsConfig_prefix)
{
    global $global_config, $module_name, $user_info, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate("sms.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('sms', sprintf($lang_module['sms'], $smsConfig_keyword . " " . $smsConfig_prefix, $user_info['email'], $smsConfig_keyword . " " . $smsConfig_prefix, $user_info['email'], $smsConfig_port));

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_wallet_pay()
 *
 * @param mixed $url_checkout
 * @param mixed $payport_content
 * @return
 */
function nv_theme_wallet_pay($url_checkout, $payport_content, $order_info, $money_info)
{
    global $global_config, $module_name, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('module_file', $module_info['module_theme']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $module_info['template']);

    if (!empty($payport_content)) {
        $xtpl->assign('PAYPORT_CONTENT', $payport_content);
        $xtpl->parse('main.payport_content');
    }

    $order_info['money_amountdisplay'] = get_display_money($order_info['money_amount']);
    $xtpl->assign('ORDER', $order_info);
    $xtpl->assign('ORDER_OBJ', $order_info['title']);

    if (!empty($url_checkout)) {
        $loop_i = 0;
        foreach ($url_checkout as $value) {
            $loop_i++;
            $xtpl->assign('DATA_PAYMENT', $value);
            if ($loop_i % 2 == 0) {
                $xtpl->parse('main.payment.paymentloop.clear_sm');
            }
            if ($loop_i % 3 == 0) {
                $xtpl->parse('main.payment.paymentloop.clear_md');
            }

            $xtpl->parse('main.payment.paymentloop');

            if (!empty($value['data']['bodytext'])) {
                $xtpl->parse('main.payment.paymentguideloop.guide');
            }
            if ($value['payment_type'] != 'direct') {
                // Thanh toán quy đổi bằng ngoại tệ khác
                $xtpl->assign('EXPAY_MSG', sprintf($lang_module['paygate_exchange_pay_msg'], $order_info['money_unit'], get_display_money($value['exchange_info']['total']) . ' ' . $value['exchange_info']['currency']));
                $xtpl->parse('main.payment.paymentguideloop.exchange');
            }
            $xtpl->parse('main.payment.paymentguideloop');
        }

        $xtpl->parse('main.payment');
    }

    // Xuất thông tin ví tiền
    $xtpl->assign('WALLET', $money_info);
    $xtpl->assign('WPAYMSG', sprintf($lang_module['paygate_wpay_msg'], $order_info['money_amountdisplay'] . ' ' . $order_info['money_unit']));

    if ($money_info['moneytotalnotformat'] < $order_info['money_amount']) {
        $xtpl->assign('LINK_RECHARGE', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;amount=' . ($order_info['money_amount'] - $money_info['moneytotalnotformat']) . '-' . $order_info['money_unit']);
        $xtpl->parse('main.wpay_cant');
    } else {
        $xtpl->parse('main.wpay_detail');
        $xtpl->parse('main.wpay_submit');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $order_info
 * @param array $row_payment
 * @param array $post
 * @param string $error
 * @return string
 */
function nv_theme_wallet_atm_pay($order_info, $row_payment, $post, $error)
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_config, $module_name, $module_captcha;

    $xtpl = new XTemplate('atm_pay.tpl', NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . "index.php?scaptcha=captcha&t=" . NV_CURRENTTIME);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
    $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
    $xtpl->assign('CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_ASSETS_DIR . "/images/refresh.png");
    $xtpl->assign('ROW_PAYMENT', $row_payment);
    $xtpl->assign('FORM_ACTION', $order_info['payurl'] . '&amp;payment=' . $row_payment['payment']);

    $xtpl->assign('DATA', $post);

    if (!empty($post['atm_filedepute_key'])) {
        $xtpl->assign('SHOW_ATM_FILEDEPUTE', ' class="hidden"');
        $xtpl->parse('main.atm_filedepute');
    } else {
        $xtpl->assign('SHOW_ATM_FILEDEPUTE', '');
    }

    if (!empty($post['atm_filebill_key'])) {
        $xtpl->assign('SHOW_ATM_FILEBILL', ' class="hidden"');
        $xtpl->parse('main.atm_filebill');
    } else {
        $xtpl->assign('SHOW_ATM_FILEBILL', '');
    }

    // Xác định có áp dụng reCaptcha hay không
    $reCaptchaPass = (!empty($global_config['recaptcha_sitekey']) and !empty($global_config['recaptcha_secretkey']) and ($global_config['recaptcha_ver'] == 2 or $global_config['recaptcha_ver'] == 3));

    // Nếu dùng reCaptcha v3
    if ($module_captcha == 'recaptcha' and $reCaptchaPass and $global_config['recaptcha_ver'] == 3) {
        $xtpl->parse('main.recaptcha3');
    }
    // Nếu dùng reCaptcha v2
    elseif ($module_captcha == 'recaptcha' and $reCaptchaPass and $global_config['recaptcha_ver'] == 2) {
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->parse('main.recaptcha');
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
