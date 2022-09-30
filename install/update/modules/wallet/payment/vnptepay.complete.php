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

$payment_config['webservice'] = nv_unhtmlspecialchars($payment_config['webservice']);

require NV_ROOTDIR . "/modules/" . $module_file . "/payment/vnptepay/libs/nusoap.php";
require NV_ROOTDIR . "/modules/" . $module_file . "/payment/vnptepay/Entries.php";

$soapClient = new SoapClient(null, array('location' => $payment_config['webservice'], 'uri' => "http://113.161.78.134/VNPTEPAY/"));

$CardCharging = new CardCharging();
$CardCharging->m_UserName = $payment_config['m_UserName'];
$CardCharging->m_PartnerID = $payment_config['m_PartnerID'];
$CardCharging->m_MPIN = $payment_config['m_MPIN'];
$CardCharging->m_Target = $user_info['username'];
$CardCharging->m_Card_DATA = $post['serial'] . ":" . $post['pin'] . ":" . "0" . ":" . $post['provider'];
$CardCharging->m_SessionID = "";
$CardCharging->m_Pass = $payment_config['m_Pass'];
$CardCharging->soapClient = $soapClient;

$transid = $payment_config['m_PartnerCode'] . date("YmdHms"); //Gen transaction id

$CardCharging->m_TransID = $transid;

$CardChargingResponse = new CardChargingResponse();
$CardChargingResponse = $CardCharging->CardCharging_();

// Giao dịch này không lưu vào giao dịch chờ duyệt, lưu thẳng vào giao dịch hoàn tất
if ($CardChargingResponse->m_Status == 1) {
    $post['status'] = 1;
    $post['money_unit'] = "VND";
    $post['money_net'] = intval($CardChargingResponse->m_RESPONSEAMOUNT);
    $post['userid'] = $user_info['userid'];
    $post['customer_id'] = $user_info['userid'];
    $post['customer_name'] = $user_info['username'];
    $post['customer_email'] = $user_info['email'];
    $post['customer_phone'] = $post['customer_address'] = $post['customer_info'] = '';
    $post['transaction_info'] = sprintf($lang_module['payment_vnpt_ok'], $array_provider[$post['provider']], $post['pin']);

    // Phí cho nhà cung cấp
    $post['money_discount'] = 0;
    $post['money_revenue'] = $post['money_net']; // Bằng với số tiền thành viên nạp

    if (!empty($row_payment['discount'])) {
        $row_payment['discount'] = floatval($row_payment['discount']);

        $post['money_discount'] = round($post['money_net'] * $row_payment['discount'] / 100, 4, PHP_ROUND_HALF_UP);
        $post['money_revenue'] = $post['money_net'] - $post['money_discount'];
    }

    $post['money_total'] = $post['money_net'];

    $id_transaction = $db->insert_id("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction
    (created_time, status, money_unit, money_total, money_net, money_discount, money_revenue,
    userid, adminid, customer_id, customer_name, customer_email, customer_phone, customer_address, customer_info,
    transaction_id, transaction_status, transaction_time, transaction_info, transaction_data, payment, provider, tokenkey) VALUES
    (" . NV_CURRENTTIME . ", '" . $post['status'] . "', '" . $post['money_unit'] . "', " . $post['money_total'] . ", " . $post['money_net'] . ", " . $post['money_discount'] . ", " . $post['money_revenue'] . ",
    " . $post['userid'] . ", 0, " . $post['customer_id'] . ", " . $db->quote($post['customer_name']) . ", " . $db->quote($post['customer_email']) . ", " . $db->quote($post['customer_phone']) . ", " . $db->quote($post['customer_address']) . ", " . $db->quote($post['customer_info']) . ",
	'0', 4, " . NV_CURRENTTIME . ", " . $db->quote($post['transaction_info']) . ", '', " . $db->quote($payment) . ", " . $db->quote($post['provider']) . ", " . $db->quote('') . ")");

    nv_wallet_money_in($user_info['userid'], $post['money_unit'], $post['money_net']);

    $contents = "<div style='text-align:center;font-weight:bold'>" . sprintf($lang_module['naptienthanhcong'], number_format($post['money_net'], 0, " ", ",")) . "</div>";
    $contents .= "<meta http-equiv=\"refresh\" content=\"5;URL=" . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=money", true) . "\" />";

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} else {
    $error = $CardChargingResponse->m_Message;
}
