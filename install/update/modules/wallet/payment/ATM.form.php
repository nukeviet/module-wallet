<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET') or !defined('NV_IS_ATM_FORM')) {
    die('Stop!!!');
}

$post['atm_sendbank'] = nv_substr($nv_Request->get_title('atm_sendbank', 'post', ''), 0, 250);
$post['atm_fracc'] = nv_substr($nv_Request->get_title('atm_fracc', 'post', ''), 0, 250);
$post['atm_time'] = nv_substr($nv_Request->get_title('atm_time', 'post', ''), 0, 250);
$post['atm_toacc'] = nv_substr($nv_Request->get_title('atm_toacc', 'post', ''), 0, 250);
$post['atm_recvbank'] = nv_substr($nv_Request->get_title('atm_recvbank', 'post', ''), 0, 250);
$post['atm_acq'] = $nv_Request->get_int('atm_acq', 'post', -1);
$post['atm_to_bank'] = '';
$post['atm_to_name'] = '';
$post['atm_to_account'] = '';
$post['transaction_data'] = '';

if (empty($post['atm_sendbank'])) {
    $atm_error = $lang_module['atm_error_sendbank'];
} elseif (empty($post['atm_fracc'])) {
    $atm_error = $lang_module['atm_error_fracc'];
} elseif (empty($post['atm_toacc'])) {
    $atm_error = $lang_module['atm_error_toacc'];
} elseif (empty($post['atm_recvbank'])) {
    $atm_error = $lang_module['atm_error_recvbank'];
}

// Kiểm tra điều kiện gọi API VietQR
if (!isset($payment_config['acq_id'][$post['atm_acq']])) {
    $vietrq_error = $lang_module['atm_vietqr_error_acq'];
} elseif ($is_vietqr) {
    $post['atm_to_bank'] = $array_banks[$payment_config['acq_id'][$post['atm_acq']]]['name'];
    $post['atm_to_name'] = $payment_config['account_name'][$post['atm_acq']];
    $post['atm_to_account'] = $payment_config['account_no'][$post['atm_acq']];
}

$file_types_allowed = ['images', 'archives', 'documents', 'adobe'];
$upload = new NukeViet\Files\Upload($file_types_allowed, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
$upload->setLanguage($lang_global);

// Lấy các file đã lưu ở Session
$array_session_file = [];
if ($nv_Request->isset_request($module_data . '_atm_files', 'session')) {
    $atm_files = $nv_Request->get_string($module_data . '_atm_files', 'session', '');
    if (!empty($atm_files)) {
        $array_session_file = json_decode($crypt->decrypt($atm_files), true);
    }
}

// File scan bản sao giấy ủy nhiệm chi
if (isset($_FILES['atm_filedepute']) and is_uploaded_file($_FILES['atm_filedepute']['tmp_name'])) {
    // Lưu file upload về thư mục tạm, xóa file tạm
    $upload_info = $upload->save_file($_FILES['atm_filedepute'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false);
    @unlink($_FILES['atm_filedepute']['tmp_name']);

    if (empty($upload_info['error'])) {
        // Đổi tên file upload được thành tên file bí mật, hủy bỏ đuôi file
        $new_basename = nv_genpass(6) . '.' . substr($upload_info['basename'], 0, 200);
        $new_filename = sha1($new_basename . $global_config['sitekey']);
        if (nv_copyfile($upload_info['name'], NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $new_filename)) {
            @chmod(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $new_filename, 0644);
            $file_key = nv_genpass(12);
            $array_session_file[$file_key] = [
                'realname' => str_replace('-', ' ', nv_string_to_filename($_FILES['atm_filedepute']['name'])), // Tên file thật (người dùng donwload về, hiển thị)
                'basename' => $new_basename // Tên file thật để xác định file lưu trên server
            ];
            $post['atm_filedepute'] = $array_session_file[$file_key]['realname']; // Tên file hiện tại
            $post['atm_filedepute_key'] = $file_key; // Khóa file hiện tại
        }
        nv_deletefile($upload_info['name']);
    } else {
        $atm_error = $lang_module['atm_error_recvbank'];
    }
} else {
    // Lấy từ request
    $post['atm_filedepute_key'] = $nv_Request->get_title('atm_filedepute_key', 'post', '');
    if (isset($array_session_file[$post['atm_filedepute_key']])) {
        $post['atm_filedepute'] = $array_session_file[$post['atm_filedepute_key']]['realname'];
    } else {
        $post['atm_filedepute_key'] = $post['atm_filedepute'] = '';
    }
}

// File hóa đơn
if (isset($_FILES['atm_filebill']) and is_uploaded_file($_FILES['atm_filebill']['tmp_name'])) {
    $upload_info = $upload->save_file($_FILES['atm_filebill'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false);
    @unlink($_FILES['atm_filebill']['tmp_name']);

    if (empty($upload_info['error'])) {
        // Đổi tên file upload được thành tên file bí mật, hủy bỏ đuôi file
        $new_basename = nv_genpass(6) . '.' . substr($upload_info['basename'], 0, 200);
        $new_filename = sha1($new_basename . $global_config['sitekey']);
        if (nv_copyfile($upload_info['name'], NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $new_filename)) {
            @chmod(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $new_filename, 0644);
            $file_key = nv_genpass(12);
            $array_session_file[$file_key] = [
                'realname' => str_replace('-', ' ', nv_string_to_filename($_FILES['atm_filebill']['name'])), // Tên file thật (người dùng donwload về, hiển thị)
                'basename' => $new_basename // Tên file thật để xác định file lưu trên server
            ];
            $post['atm_filebill'] = $array_session_file[$file_key]['realname']; // Tên file hiện tại
            $post['atm_filebill_key'] = $file_key; // Khóa file hiện tại
        }
        nv_deletefile($upload_info['name']);
    } else {
        $atm_error = $lang_module['atm_error_recvbank'];
    }
} else {
    // Lấy từ request
    $post['atm_filebill_key'] = $nv_Request->get_title('atm_filebill_key', 'post', '');
    if (isset($array_session_file[$post['atm_filebill_key']])) {
        $post['atm_filebill'] = $array_session_file[$post['atm_filebill_key']]['realname'];
    } else {
        $post['atm_filebill_key'] = $post['atm_filebill'] = '';
    }
}

// Lưu session các file upload
if (!empty($array_session_file)) {
    $nv_Request->set_Session($module_data . '_atm_files', $crypt->encrypt(json_encode($array_session_file)));
}
