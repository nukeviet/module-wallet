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

/*
 * Xử lý các dữ liệu trước khi lưu giao dịch vào CSDL
 * Tại đây không quan tâm đến các lỗi nữa
 */
$transaction_data = [];

// Các dữ liệu text
$transaction_data['to_account'] = $post['to_account'];

$post['transaction_data'] = serialize($transaction_data);
