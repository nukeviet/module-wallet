<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

$arrSQL = array();
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_exchange (id, money_unit, than_unit, exchange_from, exchange_to, time_update, status) VALUES(1, 'USD', 'VND', 1, 22675, 1312000118, 1)";
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_exchange (id, money_unit, than_unit, exchange_from, exchange_to, time_update, status) VALUES(2, 'VND', 'USD', 22675, 1, 1439725873, 1)";

$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money_sys (id, code, currency) VALUES('704', 'VND', 'Vietnam Dong')";
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money_sys (id, code, currency) VALUES('840', 'USD', 'US Dollar')";

$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_payment (
    payment, paymentname, domain, active, weight, config, discount, discount_transaction, images_button, bodytext, term, currency_support, allowedoptionalmoney
) VALUES(
    'onepaydomestic', 'Cổng thanh toán nội địa OnePay', 'http://www.onepay.vn/', '1', '1',
    'YToxMDp7czoxMjoidnBjX01lcmNoYW50IjtzOjY6Ik9ORVBBWSI7czoxNDoidnBjX0FjY2Vzc0NvZGUiO3M6ODoiRDY3MzQyQzIiO3M6MTE6InZwY19WZXJzaW9uIjtzOjE6IjIiO3M6MTE6InZwY19Db21tYW5kIjtzOjM6InBheSI7czoxMDoidnBjX0xvY2FsZSI7czoyOiJ2biI7czoyMzoidmlydHVhbFBheW1lbnRDbGllbnRVUkwiO3M6NDA6Imh0dHBzOi8vbXRmLm9uZXBheS52bi9vbmVjb21tLXBheS92cGMub3AiO3M6MTM6InNlY3VyZV9zZWNyZXQiO3M6MzI6IkEzRUZERkFCQTg2NTNERjIzNDJFOERBQzI5QjUxQUYwIjtzOjExOiJRdWVyeURSX3VybCI7czo0MjoiaHR0cDovL210Zi5vbmVwYXkudm4vb25lY29tbS1wYXkvVnBjZHBzLm9wIjtzOjg6InZwY19Vc2VyIjtzOjQ6Im9wMDEiO3M6MTI6InZwY19QYXNzd29yZCI7czo4OiJvcDEyMzQ1NiI7fQ,,',
    '1', '1600', 'https://onepay.vn/onecomm-pay/img/onepay_logo.png', '', '', 'VND', 1
)";
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_payment (
    payment, paymentname, domain, active, weight, config, discount, discount_transaction, images_button, bodytext, term, currency_support, allowedoptionalmoney
) VALUES(
    'vnptepay', 'VNPT EBAY', 'http://vnptepay.com.vn/', '1', '2',
    'YTo2OntzOjExOiJtX1BhcnRuZXJJRCI7czoxMDoiY2hhcmdpbmcwMSI7czo2OiJtX01QSU4iO3M6OToicGFqd3RsemNiIjtzOjEwOiJtX1VzZXJOYW1lIjtzOjEwOiJjaGFyZ2luZzAxIjtzOjY6Im1fUGFzcyI7czo5OiJnbXd0d2pmd3MiO3M6MTM6Im1fUGFydG5lckNvZGUiO3M6NToiMDA0NzciO3M6MTA6IndlYnNlcnZpY2UiO3M6ODQ6Imh0dHA6Ly9jaGFyZ2luZy10ZXN0Lm1lZ2FwYXkubmV0LnZuOjEwMDAxL0NhcmRDaGFyZ2luZ0dXX1YyLjAvc2VydmljZXMvU2VydmljZXM_d3NkbCI7fQ,,',
    '0', '0', 'http://vnptepay.com.vn/home/img/logo.png', '', '', 'VND', 0
)";

$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_payment_discount (payment, revenue_from, revenue_to, provider, discount) VALUES('vnptepay', '1', '1000000', 'VNP', '10')";
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_payment_discount (payment, revenue_from, revenue_to, provider, discount) VALUES('vnptepay', '1', '1000000', 'VMS', '10')";
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_payment_discount (payment, revenue_from, revenue_to, provider, discount) VALUES('vnptepay', '1', '1000000', 'VTT', '10')";
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_payment_discount (payment, revenue_from, revenue_to, provider, discount) VALUES('vnptepay', '1', '1000000', 'FPT', '10')";
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_payment_discount (payment, revenue_from, revenue_to, provider, discount) VALUES('vnptepay', '1', '1000000', 'VTC', '10')";
$arrSQL[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_payment_discount (payment, revenue_from, revenue_to, provider, discount) VALUES('vnptepay', '1', '1000000', 'MGC', '10')";

foreach ($arrSQL as $sql) {
    try {
        $db->query($sql);
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }
}
