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

if (!defined('NV_IS_USER')) {
    $redirect = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op, true);
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_redirect_encrypt($redirect));
}
$page_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$canonicalUrl = getCanonicalUrl($page_url, true, true);

$page_title = $lang_module['money'];

$array = array();
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money WHERE userid=" . $user_info['userid'];
$result = $db->query($sql);
if ($result->rowCount()) {
    while ($row = $result->fetch()) {
        $arr_temp = array(
            'userid' => $row['userid'],
            'created_time' => date("d/m/Y", $row['created_time']),
            'created_userid' => $row['created_userid'],
            'status' => $row['status'],
            'money_unit' => $row['money_unit'],
            'money_in' => get_display_money($row['money_in']),
            'money_out' => get_display_money($row['money_out']),
            'money_total' => get_display_money($row['money_total']),
            'note' => $row['note']
        );
        $array[] = array("money_unit" => $row['money_unit'], "detail" => $arr_temp);
    }

    $contents = nv_wallet_acountuser($array);
} else {
    $redirect = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true);
    $contents = nv_theme_alert($lang_module['no_account'], $lang_module['no_account1'], 'info', $redirect);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
