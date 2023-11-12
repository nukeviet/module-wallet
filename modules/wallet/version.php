<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'Wallet',
    'modfuncs' => 'main,pay,complete,money,exchange,historyexchange,recharge',
    'submenu' => 'main,money,exchange,historyexchange',
    'is_sysmod' => 1,
    'virtual' => 1,
    'version' => '4.5.04',
    'date' => 'Sunday, November 12, 2023 12:56:30 AM GMT+07:00',
    'author' => 'VINADES.,JSC <contact@vinades.vn>',
    'uploads_dir' => [$module_name],
    'note' => 'Quản lý tiền, thanh toán đơn hàng'
];
