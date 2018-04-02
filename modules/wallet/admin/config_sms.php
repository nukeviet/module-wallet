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

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$page_title = $lang_module['config_sms'];

//save
if ($nv_Request->get_string('save', 'post') != "") {
    //sms
    $array_config['allow_smsNap'] = $nv_Request->isset_request('allow_smsNap', 'post') ? 1 : 0;
    //sms VIP
    $array_config['smsConfigNap_keyword'] = $nv_Request->get_string('smsConfigNap_keyword', 'post', '');
    $array_config['smsConfigNap_prefix'] = $nv_Request->get_string('smsConfigNap_prefix', 'post', '');
    $array_config['smsConfigNap_port'] = $nv_Request->get_string('smsConfigNap_port', 'post', '');
    $array_config['smsConfigNap'] = $array_config['smsConfigNap_keyword'] . " " . $array_config['smsConfigNap_prefix'] . " " . $array_config['smsConfigNap_port'];
    unset($array_config['smsConfigNap_keyword'], $array_config['smsConfigNap_prefix'], $array_config['smsConfigNap_port']);
    //end sms VIP
    foreach ($array_config as $config_name => $config_value) {
        $db->query("REPLACE INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES('" . NV_LANG_DATA . "', " . $db->quote($module_name) . ", " . $db->quote($config_name) . ", " . $db->quote($config_value) . ")");
    }
    //$xxx->closeCursor();

    $nv_Cache->delMod('settings');
    $nv_Cache->delMod($module_name);

    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    die();
}

//showlist

$data = $module_config[$module_name];
if ($data['allow_smsNap'] == '1')
    $xtpl->assign('allow_smsNap', "checked=\"checked\"");

$temp = explode(" ", $data['smsConfigNap']);
if (sizeof($temp) == 2) {
    $data['smsConfigNap_keyword'] = $temp[0];
    $data['smsConfigNap_port'] = $temp[1];
} elseif (sizeof($temp) == 3) {
    $data['smsConfigNap_keyword'] = $temp[0];
    $data['smsConfigNap_prefix'] = $temp[1];
    $data['smsConfigNap_port'] = $temp[2];
}

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('DATA', $data);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

?>