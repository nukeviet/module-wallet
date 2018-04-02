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

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$url_checkout = array();

foreach ($global_array_payments as $row) {
    if (file_exists(NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $row['payment'] . ".checkout_url.php")) {
        $payment_config = unserialize(nv_base64_decode($row['config']));
        $payment_config['paymentname'] = $row['paymentname'];
        $payment_config['domain'] = $row['domain'];

        $images_button = $row['images_button'];
        $url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=recharge/" . $row['payment'];

        if (!empty($images_button) and file_exists(NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $images_button)) {
            $images_button = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $images_button;
        }

        $url_checkout[] = array(
            "name" => $row['paymentname'],
            "url" => $url,
            "images_button" => $images_button
        );
    }
}

// Chuyển đến trang nạp nếu chỉ có một cổng thanh toán
if (sizeof($url_checkout) == 1) {
    $url = current($url_checkout);
    nv_redirect_location(str_replace('&amp;', '&', $url['url']));
}

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$contents = nv_theme_wallet_main($url_checkout, $module_config[$module_name]['payport_content']);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
