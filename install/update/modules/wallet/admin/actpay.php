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

$payment = $nv_Request->get_string('id', 'post,get', '');
$value = $nv_Request->get_int('value', 'post,get', 0);
$table = $db_config['prefix'] . "_" . $module_data . "_payment";
$contents = $lang_module['active_change_not_complete'];
if (!empty($payment)) {
    $value = $db->query("SELECT active FROM " . $table . " WHERE payment=" . $db->quote($payment))->fetchColumn();
    $value = ($value == '1') ? '0' : '1';
    $query = "UPDATE " . $table . " SET active=" . $value . " WHERE payment=" . $db->quote($payment);
    if ($db->query($query)) {
        //$xxx->closeCursor();
        $contents = $lang_module['active_change_complete'];
    }
}
$nv_Cache->delMod($module_name);
echo $contents;
