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

$payment = $nv_Request->get_title('oid', 'post', '');
$new_weight = $nv_Request->get_int('w', 'post', 0);

$sql = "SELECT payment FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE payment!=" . $db->quote($payment) . " ORDER BY weight ASC";
$lists = $db->query($sql)->fetchAll();

$weight = 0;
foreach ($lists as $row) {
    $weight++;
    if ($weight == $new_weight) {
        $weight++;
    }
    $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_payment SET weight=" . $weight . " WHERE payment=" . $db->quote($row['payment']));
}
$db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_payment SET weight=" . $new_weight . " WHERE payment=" . $db->quote($payment));
$nv_Cache->delMod($module_name);

nv_htmlOutput("OK_" . $payment);
