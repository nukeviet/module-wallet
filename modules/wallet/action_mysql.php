<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_FILE_MODULES')) die('Stop!!!');

$sql_drop_module = array();

$result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $module_data . "\_money%'");
$num_table = intval($result->rowCount());

if (empty($num_table)) {
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_epay_log";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_exchange";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_exchange_log";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_money";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_money_sys";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_payment";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_payment_discount";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_smslog";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_transaction";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_orders";

    $sql_create_module = $sql_drop_module;
    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_epay_log(
      id int(11) NOT NULL AUTO_INCREMENT,
      time int(11) NOT NULL DEFAULT '0',
      telco char(3) NOT NULL DEFAULT '',
      code varchar(30) NOT NULL DEFAULT '',
      userid int(11) NOT NULL DEFAULT '0',
      status tinyint(4) NOT NULL DEFAULT '0',
      SessionID varchar(255) NOT NULL DEFAULT '',
      money_card int(11) NOT NULL DEFAULT '0',
      money_site int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (id),
      KEY userid (userid),
      KEY time (time),
      KEY telco (telco,code)
    ) ENGINE=MyISAM";

    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_exchange(
      id int(10) unsigned NOT NULL AUTO_INCREMENT,
      money_unit char(3) NOT NULL DEFAULT '',
      than_unit char(3) NOT NULL DEFAULT '',
      exchange double NOT NULL DEFAULT '0',
      time_update int(11) NOT NULL DEFAULT '0',
      status tinyint(4) NOT NULL DEFAULT '0',
      PRIMARY KEY (id),
      UNIQUE KEY type (money_unit,than_unit)
    ) ENGINE=MyISAM";

    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_exchange_log(
      log_id int(11) NOT NULL AUTO_INCREMENT,
      money_unit char(3) NOT NULL DEFAULT '',
      than_unit char(3) NOT NULL DEFAULT '',
      exchange double NOT NULL DEFAULT '0',
      time_begin int(11) NOT NULL DEFAULT '0',
      time_end int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (log_id)
    ) ENGINE=MyISAM";

    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_money(
      userid int(11) NOT NULL DEFAULT '0',
      created_time int(11) NOT NULL DEFAULT '0',
      created_userid int(11) NOT NULL DEFAULT '0',
      status tinyint(4) NOT NULL DEFAULT '0',
      money_unit char(3) NOT NULL DEFAULT '',
      money_in double NOT NULL DEFAULT '0',
      money_out double NOT NULL DEFAULT '0',
      money_total double NOT NULL DEFAULT '0',
      note text NOT NULL,
      tokenkey varchar(32) NOT NULL DEFAULT '',
      UNIQUE KEY userid (userid,money_unit)
    ) ENGINE=MyISAM";

    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_money_sys(
      id int(10) unsigned NOT NULL AUTO_INCREMENT,
      code char(3) NOT NULL DEFAULT '',
      currency varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (id)
    ) ENGINE=MyISAM";

    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_payment(
      payment varchar(100) NOT NULL DEFAULT '',
      paymentname varchar(255) NOT NULL DEFAULT '',
      domain varchar(255) NOT NULL DEFAULT '',
      active tinyint(4) NOT NULL DEFAULT '0',
      weight int(11) NOT NULL DEFAULT '0',
      config text NOT NULL,
      discount double NOT NULL DEFAULT '0' COMMENT 'Phí cho nhà cung cấp dịch vụ, phần này chỉ làm đối số để thống kê',
      discount_transaction double NOT NULL DEFAULT '0' COMMENT 'Phí giao dịch',
      images_button varchar(255) NOT NULL DEFAULT '',
      bodytext mediumtext NOT NULL,
      term mediumtext NOT NULL,
      currency_support varchar(255) NOT NULL DEFAULT '' COMMENT 'Các loại tiền tệ hỗ trợ thanh toán',
      allowedoptionalmoney tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Cho phép thanh toán số tiền tùy ý hay không',
      PRIMARY KEY (payment)
    ) ENGINE=MyISAM";

    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_payment_discount(
      payment varchar(100) NOT NULL DEFAULT '' COMMENT 'Cổng thanh toán',
      revenue_from double NOT NULL DEFAULT '0' COMMENT 'Doanh thu từ: Quan hệ lớn hơn hoặc bằng',
      revenue_to double NOT NULL DEFAULT '0' COMMENT 'Doanh thu đến: Quan hệ nhỏ hơn',
      provider varchar(10) NOT NULL DEFAULT '0' COMMENT 'Nhà cung cấp',
      discount double NOT NULL DEFAULT '0' COMMENT 'Mức phí %',
      UNIQUE KEY payment (payment,revenue_from,revenue_to,provider)
    ) ENGINE=MyISAM";

    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_smslog(
      id int(12) unsigned NOT NULL AUTO_INCREMENT,
      User_ID varchar(15) NOT NULL DEFAULT '',
      Service_ID varchar(15) NOT NULL DEFAULT '',
      Command_Code varchar(160) NOT NULL DEFAULT '',
      Message varchar(160) NOT NULL DEFAULT '',
      Request_ID varchar(160) NOT NULL DEFAULT '',
      set_time int(11) NOT NULL DEFAULT '0',
      active tinyint(5) NOT NULL DEFAULT '0',
      client_ip varchar(25) NOT NULL DEFAULT '',
      PRIMARY KEY (id),
      KEY User_ID (User_ID),
      KEY set_time (set_time)
    ) ENGINE=MyISAM";

    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_transaction(
      id int(11) NOT NULL AUTO_INCREMENT,
      created_time int(11) NOT NULL DEFAULT '0' COMMENT 'Ngày khởi tạo giao dịch',
      status tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Tác động: 1 cộng tiền, -1 trừ tiền',
      money_unit char(3) NOT NULL DEFAULT '',
      money_total double NOT NULL DEFAULT '0' COMMENT 'Số tiền thực cập nhật vào tài khoản thành viên',
      money_net double NOT NULL DEFAULT '0' COMMENT 'Số tiền thành viên thực hiện giao dịch',
      money_discount double NOT NULL DEFAULT '0' COMMENT 'Phí doanh nghiệp phải trả cho nhà cung cấp dịch vụ',
      money_revenue double NOT NULL DEFAULT '0' COMMENT 'Lợi nhuận mà doanh nghiệp đạt được',
      userid int(11) NOT NULL DEFAULT '0' COMMENT 'ID thành viên có tài khoản được tác động',
      adminid int(11) NOT NULL DEFAULT '0' COMMENT 'ID admin thực hiện giao dịch, nếu có giá trị này sẽ không tính vào doanh thu khi thống kê',
      order_id int(11) NOT NULL DEFAULT '0' COMMENT 'ID giao dịch nếu là thanh toán các đơn hàng từ module khác',
      customer_id int(11) NOT NULL DEFAULT '0' COMMENT 'ID người thực hiện giao dịch (Nạp tiền vào tài khoản)',
      customer_name varchar(255) NOT NULL DEFAULT '',
      customer_email varchar(255) NOT NULL DEFAULT '',
      customer_phone varchar(255) NOT NULL DEFAULT '',
      customer_address varchar(255) NOT NULL DEFAULT '',
      customer_info text NOT NULL,
      transaction_id varchar(255) NOT NULL DEFAULT '',
      transaction_type smallint(5) NOT NULL DEFAULT '-1' COMMENT 'Loại giao dịch',
      transaction_status int(11) NOT NULL DEFAULT '0' COMMENT 'Trạng thái giao dịch được quy ước chuẩn theo module',
      transaction_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian thực hiện thanh toán giao dịch',
      transaction_info text NOT NULL,
      transaction_data text NOT NULL,
      payment varchar(50) NOT NULL DEFAULT '' COMMENT 'Cổng thanh toán sử dụng',
      provider varchar(50) NOT NULL DEFAULT '' COMMENT 'Nhà cung cấp thẻ sử dụng nếu như đây là cổng thanh toán nạp thẻ, nếu không cần bỏ trống',
      tokenkey varchar(32) NOT NULL DEFAULT '',
      PRIMARY KEY (id),
      KEY userid (userid),
      KEY adminid (adminid),
      KEY customer_id (customer_id),
      KEY created_time (created_time),
      KEY customer_name (customer_name(250)),
      KEY customer_email (customer_email(250)),
      KEY transaction_type (transaction_type)
    ) ENGINE=MyISAM";

    // Các đơn hàng từ module khác
    $sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $module_data . "_orders (
      id int(11) NOT NULL AUTO_INCREMENT,
      order_mod varchar(100) NOT NULL DEFAULT '' COMMENT 'Module title của module thực hiện đơn hàng',
      order_id varchar(100) NOT NULL DEFAULT '' COMMENT 'ID đơn hàng',
      order_message text NOT NULL COMMENT 'Message gửi cho cổng thanh toán',
      order_object varchar(250) NOT NULL DEFAULT '' COMMENT 'Đối tượng thanh toán ví dụ: Giỏ hàng, sản phẩn, ứng dụng...',
      order_name varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên đối tượng',
      money_amount double NOT NULL DEFAULT '0' COMMENT 'Số tiền thanh toán',
      money_unit varchar(3) NOT NULL DEFAULT '' COMMENT 'Loại tiền tệ',
      secret_code varchar(50) NOT NULL DEFAULT '' COMMENT 'Mã bí mật của mỗi đơn hàng, không trùng lặp',
      url_back text NOT NULL COMMENT 'Dữ liệu trả về khi thanh toán xong',
      url_admin text NOT NULL COMMENT 'Url trang quản trị đơn hàng',
      add_time int(11) NOT NULL DEFAULT '0',
      update_time int(11) NOT NULL DEFAULT '0',
      paid_status varchar(100) NOT NULL DEFAULT '' COMMENT 'Trạng thái giao dịch',
      paid_id varchar(50) NOT NULL DEFAULT '' COMMENT 'ID giao dịch',
      paid_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật của status kia',
      PRIMARY KEY (id),
      UNIQUE KEY order_key (order_mod, order_id),
      UNIQUE KEY secret_code (secret_code),
      KEY paid_status(paid_status)
    ) ENGINE=MyISAM";
}

$sql = "SELECT * FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang ='" . $lang . "' AND module='" . $module_name . "'";
$result = $db->query($sql);
if ($result->rowCount() == 0) {
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allow_smsNap', '0')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'smsConfigNap_keyword', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'smsConfigNap_port', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'smsConfigNap_prefix', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'smsConfigNap', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'minimum_amount', 'a:2:{s:3:\"VND\";s:46:\"10000,20000,50000,100000,200000,500000,1000000\";s:3:\"USD\";s:22:\"5,10,20,50,100,200,500\";}')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'payport_content', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'recharge_rate', '')";
}
