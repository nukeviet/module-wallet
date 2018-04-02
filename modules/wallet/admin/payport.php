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

$page_title = $lang_module['setup_payment'];

/**
 * drawselect_number()
 *
 * @param string $select_name
 * @param integer $number_start
 * @param integer $number_end
 * @param integer $number_curent
 * @param string $func_onchange
 * @return
 */
function drawselect_number($select_name = "", $number_start = 0, $number_end = 1, $number_curent = 0, $func_onchange = "")
{
    $html = "<select class=\"form-control\" name=\"" . $select_name . "\" onchange=\"" . $func_onchange . "\">";

    for ($i = $number_start; $i < $number_end; $i++) {
        $select = ($i == $number_curent) ? "selected=\"selected\"" : "";
        $html .= "<option value=\"" . $i . "\"" . $select . ">" . $i . "</option>";
    }

    $html .= "</select>";

    return $html;
}

// Các cổng thanh toán trong CSDL
$array_setting_payment = array();
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment ORDER BY weight ASC";
$result = $db->query($sql);
$all_page = $result->rowCount();
while ($row = $result->fetch()) {
    $array_setting_payment[$row['payment']] = $row;
}

// Các cổng thanh toán trên máy chủ
$check_config_payment = "/^([a-zA-Z0-9\-\_]+)\.config\.ini$/";
$payment_funcs = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/payment', $check_config_payment);

if (!empty($payment_funcs)) {
    $payment_funcs = preg_replace($check_config_payment, "\\1", $payment_funcs);
}

$array_setting_payment_key = array_keys($array_setting_payment);
$array_payment_other = array();

foreach ($payment_funcs as $payment) {
    $xml = simplexml_load_file(NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.config.ini');

    if ($xml !== false) {
        $xmlconfig = $xml->xpath('config');

        $config = $xmlconfig[0];
        $array_config = array();
        $array_config_title = array();

        foreach ($config as $key => $value) {
            $config_lang = $value->attributes();

            if (isset($config_lang[NV_LANG_INTERFACE])) {
                $lang = (string )$config_lang[NV_LANG_INTERFACE];
            } else {
                $lang = $key;
            }

            $array_config[$key] = trim($value);
            $array_config_title[$key] = $lang;
        }

        $array_payment_other[$payment] = array(
            'payment' => $payment,
            'paymentname' => trim($xml->name),
            'domain' => trim($xml->domain),
            'images_button' => trim($xml->images_button),
            'config' => $array_config,
            'titlekey' => $array_config_title,
            'currency_support' => trim($xml->currency),
            'allowedoptionalmoney' => intval($xml->optional) ? 1 : 0
        );

        unset($config, $xmlconfig, $xml);
    }
}

$data_pay = array();

// Lấy dữ liệu khi tích hợp cổng thanh toán mới
$payment = $nv_Request->get_string('payment', 'get', '');
if (!empty($payment)) {
    // Get data have not in database
    if (!in_array($payment, $array_setting_payment_key)) {
        if (!empty($array_payment_other[$payment])) {
            $weight = $db->query("SELECT max(weight) FROM " . $db_config['prefix'] . "_" . $module_data . "_payment")->fetchColumn();
            $weight = intval($weight) + 1;

            $sql = "REPLACE INTO " . $db_config['prefix'] . "_" . $module_data . "_payment (
                payment, paymentname, domain, active, weight, config,images_button, bodytext, term, currency_support, allowedoptionalmoney
            ) VALUES (
                " . $db->quote($payment) . ", " . $db->quote($array_payment_other[$payment]['paymentname']) . ",
                " . $db->quote($array_payment_other[$payment]['domain']) . ", '0', '" . $weight . "',
                '" . nv_base64_encode(serialize($array_payment_other[$payment]['config'])) . "',
                " . $db->quote($array_payment_other[$payment]['images_button']) . ", '', '', " . $db->quote($array_payment_other[$payment]['currency_support']) . ",
                " . $array_payment_other[$payment]['allowedoptionalmoney'] . "
            )";
            $db->query($sql);

            $nv_Cache->delMod($module_name);

            $data_pay = $array_payment_other[$payment];
        }
    }

    // Get data have in database
    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE payment=" . $db->quote($payment);
    $result = $db->query($sql);
    $data_pay = $result->fetch();
}

if ($nv_Request->isset_request('saveconfigpaymentedit', 'post')) {
    $payment = $nv_Request->get_title('payment', 'post', '', 0);
    $paymentname = $nv_Request->get_title('paymentname', 'post', '', 0);
    $domain = $nv_Request->get_title('domain', 'post', '', 0);
    $images_button = $nv_Request->get_title('images_button', 'post', '', 0);
    $active = $nv_Request->get_int('active', 'post', 0);
    $array_config = $nv_Request->get_array('config', 'post', array());
    $bodytext = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
    $bodytext = nv_editor_nl2br($bodytext);
    $term = $nv_Request->get_editor('term', '', NV_ALLOWED_HTML_TAGS);
    $term = nv_editor_nl2br($term);
    $discount = $nv_Request->get_float('discount', 'post', '', 0);
    $discount_transaction = $nv_Request->get_float('discount_transaction', 'post', '', 0);

    if ($discount >= 100) {
        $discount = 0;
    }

    if (!nv_is_url($images_button) and file_exists(NV_DOCUMENT_ROOT . $images_button)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/");
        $images_button = substr($images_button, $lu);
    } elseif (!nv_is_url($images_button)) {
        $images_button = "";
    }

    $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_payment
			SET paymentname = " . $db->quote($paymentname) . ", domain = " . $db->quote($domain) . ",
			active=" . $active . ", config = '" . nv_base64_encode(serialize($array_config)) . "',
			images_button=" . $db->quote($images_button) . ", bodytext=" . $db->quote($bodytext) . ", term=" . $db->quote($term) . ",
			discount = " . $discount . ", discount_transaction= " . $discount_transaction . "
			WHERE payment = " . $db->quote($payment) . " LIMIT 1";
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_product', "edit " . $paymentname, $admin_info['userid']);
    $nv_Cache->delMod($module_name);

    nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
}

$xtpl = new XTemplate("payport.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);

if (!empty($array_setting_payment) and empty($data_pay)) {
    foreach ($array_setting_payment as $value) {
        $value['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&amp;payment=" . $value['payment'];
        $value['link_config'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=config_payment&amp;payment=" . $value['payment'];
        $value['active'] = ($value['active'] == "1") ? "checked=\"checked\"" : "";
        $value['slect_weight'] = drawselect_number($value['payment'], 1, $all_page + 1, $value['weight'], "nv_chang_pays('" . $value['payment'] . "',this,url_change_weight,url_back);");

        $xtpl->assign('DATA_PM', $value);

        if ($value['payment'] == 'vnptepay') {
            $xtpl->parse('main.listpay.paymentloop.vnptepay');
        }

        $xtpl->parse('main.listpay.paymentloop');
    }

    $xtpl->assign('url_back', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    $xtpl->assign('url_change', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=changepay");
    $xtpl->assign('url_active', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=actpay");
    $xtpl->parse('main.listpay');
}

if (!empty($array_payment_other) and empty($data_pay)) {
    $a = 1;
    foreach ($array_payment_other as $pay => $value) {
        if (!in_array($pay, $array_setting_payment_key)) {
            $value['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&amp;payment=" . $value['payment'];
            $value['STT'] = $a;

            $xtpl->assign('ODATA_PM', $value);
            $xtpl->parse('main.olistpay.opaymentloop');
            $a++;
        }
    }

    if ($a > 1)
        $xtpl->parse('main.olistpay');
}

if (!empty($data_pay)) {
    if (defined('NV_EDITOR')) {
        require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
    }

    $bodytext = nv_editor_br2nl($data_pay['bodytext']);
    $term = nv_editor_br2nl($data_pay['term']);

    if (!empty($bodytext)) {
        $bodytext = nv_htmlspecialchars($bodytext);
    }

    if (!empty($term)) {
        $term = nv_htmlspecialchars($term);
    }

    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $bodytext = nv_aleditor("bodytext", '100%', '300px', $bodytext);
        $term = nv_aleditor("term", '100%', '300px', $term);
    } else {
        $bodytext = "<textarea style=\"width:100%;height:300px\" name=\"bodytext\" id=\"bodytext\">" . $bodytext . "</textarea>";
        $term = "<textarea style=\"width:100%;height:300px\" name=\"term\" id=\"term\">" . $term . "</textarea>";
    }

    if (!empty($data_pay['images_button']) and file_exists(NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $data_pay['images_button'])) {
        $data_pay['images_button'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $data_pay['images_button'];
    }

    $xtpl->assign('EDITPAYMENT', sprintf($lang_module['editpayment'], $data_pay['payment']));

    $array_config = unserialize(nv_base64_decode($data_pay['config']));

    $arkey_title = array();

    if (!empty($array_payment_other[$data_pay['payment']]['titlekey'])) {
        $arkey_title = $array_payment_other[$data_pay['payment']]['titlekey'];
    }

    foreach ($array_config as $key => $value) {
        if (isset($arkey_title[$key])) {
            $lang = (string )$arkey_title[$key];
        } else {
            $lang = $key;
        }

        $value = $array_config[$key];

        $xtpl->assign('CONFIG_LANG', $lang);
        $xtpl->assign('CONFIG_NAME', $key);
        $xtpl->assign('CONFIG_VALUE', $value);
        $xtpl->parse('main.paymentedit.config');
    }

    $data_pay['active'] = ($data_pay['active'] == "1") ? "checked=\"checked\"" : "";

    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('DATA', $data_pay);
    $xtpl->assign('BODYTEXT', $bodytext);
    $xtpl->assign('TERM', $term);

    $config_link = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=config";
    $xtpl->assign('DISCOUNT_MESSAGE', sprintf($lang_module['payport_discount_note'], $lang_module['payport_discount1'], $lang_module['payport_discount_transaction'], $lang_module['config_module'], $config_link, $lang_module['config_module']));

    if ($data_pay['payment'] != 'vnptepay') {
        $xtpl->parse('main.paymentedit.onepay');
    }

    $xtpl->parse('main.paymentedit');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
