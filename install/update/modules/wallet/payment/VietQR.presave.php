<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET') or !defined('NV_IS_VIETQR_FORM')) {
    die('Stop!!!');
}

/*
 * Xử lý các dữ liệu trước khi lưu giao dịch vào CSDL
 * Tại đây không quan tâm đến các lỗi nữa
 */
$transaction_data = [];

// Các dữ liệu text
$transaction_data['atm_toacc'] = $post['atm_toacc'];
$transaction_data['atm_recvbank'] = $post['atm_recvbank'];

// Các file xử lý kiểu text: realname|basename
$transaction_data['vietqr_screenshots'] = '';

// Xử lý các file
if (!empty($post['vietqr_screenshots_key']) and isset($array_session_file[$post['vietqr_screenshots_key']])) {
    $basename = $array_session_file[$post['vietqr_screenshots_key']]['basename'];
    $new_filename = sha1($basename . $global_config['sitekey']);
    while (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $new_filename)) {
        $basename = preg_replace('/^([a-zA-Z0-9]+)\./', nv_genpass(6) . '.', $basename);
        $new_filename = sha1($basename . $global_config['sitekey']);
    }
    $basename_old = $array_session_file[$post['vietqr_screenshots_key']]['basename'];
    $old_filename = sha1($basename_old . $global_config['sitekey']);
    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $old_filename) and nv_copyfile(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $old_filename, NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $new_filename)) {
        $transaction_data['vietqr_screenshots'] = $array_session_file[$post['vietqr_screenshots_key']]['realname'] . '|' . $new_filename;
        nv_deletefile(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $old_filename);
    }
}

$post['transaction_data'] = serialize($transaction_data);
