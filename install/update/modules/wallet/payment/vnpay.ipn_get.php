<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET')) {
    die('Stop!!!');
}

$returnData = [];

// Kiểm tra IP
if (!empty($payment_config['vnp_IPIPN'])) {
    $ipAllowed = false;
    $ipList = array_map('trim', explode(',', $payment_config['vnp_IPIPN']));
    foreach ($ipList as $ip) {
        if ($ip == $client_info['ip']) {
            $ipAllowed = true;
        }
    }
    if (!$ipAllowed) {
        // Cảnh báo qua thông báo hệ thống
        try {
            if (!empty($payment_config['IPNAlertNoti'])) {
                $notification_content = [
                    'ip' => NV_CLIENT_IP,
                    'payment' => $payment,
                    'time' => NV_CURRENTTIME
                ];
                nv_insert_notification($module_name, 'payport_ipn_alert', $notification_content, 0, 0, 0, 1);
            }
        } catch (Exception $exp) {
            trigger_error(print_r($exp, true));
        }

        // Cảnh báo qua email
        try {
            if (!empty($payment_config['IPNAlert']) and !empty($payment_config['IPNAlertEmail'])) {
                $_emails = array_filter(array_unique(array_map("trim", explode(',', $payment_config['IPNAlertEmail']))));
                $emails = [];
                foreach ($_emails as $_email) {
                    if (nv_check_valid_email($_email) ==  '') {
                        $emails[] = $_email;
                    }
                }
                if (!empty($emails)) {
                    $link = NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=ipn-logs';
                    $message = sprintf($lang_module['email_ipn_alert_c'], $payment, NV_CLIENT_IP, nv_date('H:i:s d/m/Y', NV_CURRENTTIME), NV_USER_AGENT, $link, $link);
                    nv_sendmail([$global_config['site_name'], $global_config['site_email']], $emails, $lang_module['email_ipn_alert_s'], $message);
                }
            }
        } catch (Exception $exp) {
            trigger_error(print_r($exp, true));
        }

        $returnData['RspCode'] = '03';
        $returnData['Message'] = 'Wrong IP';
        nv_jsonOutput($returnData);
    }
}

// Đối với VNPay chỉ cần gọi lại file complete để xác định
require NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.complete.php';

// Lỗi không xác định
if (!empty($error)) {
    $returnData['RspCode'] = '99';
    $returnData['Message'] = $error;
    nv_jsonOutput($returnData);
}

// Sai checksum
if ($responseData['transaction_status'] == 5) {
    $returnData['RspCode'] = '97';
    $returnData['Message'] = 'Wrong checksum';
    nv_jsonOutput($returnData);
}

// Sau khi đã kiểm tra cơ bản hoàn trả lại để module xử lý
