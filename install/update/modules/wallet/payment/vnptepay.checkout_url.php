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

$post = [];
$error = "";

$post['provider'] = 'VNP';
$post['pin'] = '';
$post['serial'] = '';
$post['transaction_info'] = '';
$post['check_term'] = 0;

$array_provider = [
    'VNP' => 'Vinaphone',
    'VMS' => 'Mobifone',
    'VTT' => 'Viettel',
    'FPT' => 'FPT',
    'VTC' => 'VTC Vcoin',
    'MGC' => 'MegaCard'
];

if ($nv_Request->isset_request('fsubmit', 'post')) {
    $post['pin'] = $nv_Request->get_title('pin', 'post', ""); // Mã PIN
    $post['serial'] = $nv_Request->get_title('serial', 'post', ""); // Số seri
    $post['provider'] = $nv_Request->get_title('provider', 'post', ""); // Loại thẻ
    $post['transaction_info'] = $nv_Request->get_title('transaction_info', 'post', '');
    $post['check_term'] = $nv_Request->get_int('check_term', 'post', 0);

    unset($fcode);
    if ($module_captcha == 'recaptcha') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
        $fcode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } elseif ($module_captcha == 'captcha') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
        $fcode = $nv_Request->get_title('fcode', 'post', '');
    }

    if (empty($post['pin'])) {
        $error = $lang_module['vnpt_error_pin'];
    } elseif (in_array($post['provider'], array('VTT', 'FPT', 'VTC', 'VNP', 'VMS')) and empty($post['serial'])) {
        $error = $lang_module['vnpt_error_serial'];
    } elseif (!isset($array_provider[$post['provider']])) {
        $error = $lang_module['vnpt_error_provider'];
    } elseif ($post['check_term'] != 1 and !empty($row_payment['term'])) {
        $error = $lang_module['error_check_term'];
    } elseif (isset($fcode) and !nv_capcha_txt($fcode, $module_captcha)) {
        $error = ($module_captcha == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'];
    } else {
        require NV_ROOTDIR . "/modules/" . $module_file . "/payment/vnptepay.complete.php";
    }
}

$xtpl = new XTemplate("vnptepay.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "/" . $payment);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

$post['check_term'] = empty($post['check_term']) ? '' : ' checked="checked"';

$xtpl->assign('ROW_PAYMENT', $row_payment);
$xtpl->assign('DATA', $post);

// Thông tin về cổng
if (!empty($row_payment['bodytext'])) {
    $xtpl->parse('main.bodytext');
}

if (!empty($row_payment['term'])) {
    $xtpl->parse('main.term');
}

foreach ($array_provider as $_provider_key => $_provider_name) {
    $provider = array(
        'key' => $_provider_key,
        'title' => $_provider_name,
        'selected' => $_provider_key == $post['provider'] ? ' selected="selected"' : ''
    );

    $xtpl->assign('PROVIDER', $provider);
    $xtpl->parse('main.provider');
}

if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
    // Nếu dùng reCaptcha v3
    $xtpl->parse('main.recaptcha3');
} elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
    // Nếu dùng reCaptcha v2
    $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
    $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
    $xtpl->parse('main.recaptcha');
} elseif ($module_captcha == 'captcha') {
    $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
    $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
    $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
    $xtpl->assign('CAPTCHA_REFR_SRC', NV_STATIC_URL . NV_ASSETS_DIR . '/images/refresh.png');
    $xtpl->assign('NV_GFX_NUM', NV_GFX_NUM);
    $xtpl->parse('main.captcha');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
