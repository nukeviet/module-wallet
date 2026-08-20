<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET') or !defined('NV_IS_SEPAY_FORM')) {
    die('Stop!!!');
}

$post['to_account'] = nv_substr($nv_Request->get_title('to_account', 'post', ''), 0, 250);
$post['transaction_data'] = '';

if (empty($post['to_account'])) {
    $sepay_error = $lang_module['atm_error_toacc1'];
}
