<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (!defined('NV_IS_UPDATE')) {
    die('Stop!!!');
}

$nv_update_config = array();

// Kieu nang cap 1: Update; 2: Upgrade
$nv_update_config['type'] = 1;

// ID goi cap nhat
$nv_update_config['packageID'] = 'NVUWALLET4502';

// Cap nhat cho module nao, de trong neu la cap nhat NukeViet, ten thu muc module neu la cap nhat module
$nv_update_config['formodule'] = 'wallet';

// Thong tin phien ban, tac gia, ho tro
$nv_update_config['id'] = 269;
$nv_update_config['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$nv_update_config['note'] = '';
$nv_update_config['release_date'] = 1664008363;
$nv_update_config['support_website'] = 'https://github.com/nukeviet/module-wallet/tree/to-4.5.02';
$nv_update_config['to_version'] = '4.5.02';
$nv_update_config['allow_old_version'] = array('4.3.01', '4.5.00');

// 0:Nang cap bang tay, 1:Nang cap tu dong, 2:Nang cap nua tu dong
$nv_update_config['update_auto_type'] = 1;

$nv_update_config['lang'] = array();
$nv_update_config['lang']['vi'] = array();

// Tiếng Việt
$nv_update_config['lang']['vi']['nv_up_f1'] = 'Cập nhật CSDL phiên bản 4.3.00';
$nv_update_config['lang']['vi']['nv_up_f2'] = 'Cập nhật CSDL phiên bản 4.3.02';
$nv_update_config['lang']['vi']['nv_up_f3'] = 'Cập nhật CSDL phiên bản 4.5.00';
$nv_update_config['lang']['vi']['nv_up_finish'] = 'Đánh dấu phiên bản mới';

$nv_update_config['tasklist'] = array();

$nv_update_config['tasklist'][] = array(
    'r' => '4.3.01',
    'rq' => 1,
    'l' => 'nv_up_f1',
    'f' => 'nv_up_f1'
);

$nv_update_config['tasklist'][] = array(
    'r' => '4.3.02',
    'rq' => 1,
    'l' => 'nv_up_f2',
    'f' => 'nv_up_f2'
);
$nv_update_config['tasklist'][] = array(
    'r' => '4.5.00',
    'rq' => 1,
    'l' => 'nv_up_f3',
    'f' => 'nv_up_f3'
);

$nv_update_config['tasklist'][] = array(
    'r' => '4.5.02',
    'rq' => 1,
    'l' => 'nv_up_finish',
    'f' => 'nv_up_finish'
);

// Danh sach cac function
/*
Chuan hoa tra ve:
array(
'status' =>
'complete' =>
'next' =>
'link' =>
'lang' =>
'message' =>
);
status: Trang thai tien trinh dang chay
- 0: That bai
- 1: Thanh cong
complete: Trang thai hoan thanh tat ca tien trinh
- 0: Chua hoan thanh tien trinh nay
- 1: Da hoan thanh tien trinh nay
next:
- 0: Tiep tuc ham nay voi "link"
- 1: Chuyen sang ham tiep theo
link:
- NO
- Url to next loading
lang:
- ALL: Tat ca ngon ngu
- NO: Khong co ngon ngu loi
- LangKey: Ngon ngu bi loi vi,en,fr ...
message:
- Any message
Duoc ho tro boi bien $nv_update_baseurl de load lai nhieu lan mot function
Kieu cap nhat module duoc ho tro boi bien $old_module_version
*/

$array_modlang_update = array();
$array_modtable_update = array();

// Lay danh sach ngon ngu
$result = $db->query("SELECT lang FROM " . $db_config['prefix'] . "_setup_language WHERE setup=1");
while (list($_tmp) = $result->fetch(PDO::FETCH_NUM)) {
    $array_modlang_update[$_tmp] = array("lang" => $_tmp, "mod" => array());

    // Get all module
    $result1 = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $_tmp . "_modules WHERE module_file=" . $db->quote($nv_update_config['formodule']));
    while (list($_modt, $_modd) = $result1->fetch(PDO::FETCH_NUM)) {
        $array_modlang_update[$_tmp]['mod'][] = array("module_title" => $_modt, "module_data" => $_modd);
        $array_modtable_update[] = $db_config['prefix'] . "_" . $_tmp . "_" . $_modd;
    }
}

/**
 * nv_up_f1()
 *
 * @return
 *
 */
function nv_up_f1()
{
    global $nv_update_baseurl, $db, $db_config, $nv_Cache, $array_modlang_update, $nv_update_config;
    $return = array(
        'status' => 1,
        'complete' => 1,
        'next' => 1,
        'link' => 'NO',
        'lang' => 'NO',
        'message' => ''
    );

    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_exchange
            CHANGE exchange exchange DOUBLE NOT NULL DEFAULT '1' "
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_exchange
            ADD exchange_from DOUBLE NOT NULL DEFAULT '1' AFTER than_unit "
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }

    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_exchange_log
            CHANGE exchange exchange DOUBLE NOT NULL DEFAULT '1' "
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_exchange_log
            ADD exchange_from DOUBLE NOT NULL DEFAULT '1' AFTER than_unit "
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }

    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_orders
            ADD paid_id VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'ID giao dịch' AFTER paid_status "
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }


    foreach ($array_modlang_update as $lang => $array_mod) {
        foreach ($array_mod['mod'] as $module_info) {
            try {
                $db->query("INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (
                    lang, module, config_name, config_value
                ) VALUES 
                    ('" . $lang . "', '" . $module_info['module_data'] . "', 'allow_exchange_pay', '1')
                ");
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }
    }
    return $return;
}

/**
 * nv_up_f2()
 *
 * @return
 *
 */
function nv_up_f2()
{
    global $nv_update_baseurl, $db, $db_config, $nv_Cache, $array_modlang_update, $nv_update_config;
    $return = array(
        'status' => 1,
        'complete' => 1,
        'next' => 1,
        'link' => 'NO',
        'lang' => 'NO',
        'message' => ''
    );

    foreach ($array_modlang_update as $lang => $array_mod) {
        foreach ($array_mod['mod'] as $module_info) {
            try {
                $db->query("INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (
                    lang, module, config_name, config_value
                ) VALUES 
                    ('" . $lang . "', '" . $module_info['module_data'] . "', 'accountants_emails', ''),
                    ('" . $lang . "', '" . $module_info['module_data'] . "', 'transaction_expiration_time', '0'),
                    ('" . $lang . "', '" . $module_info['module_data'] . "', 'next_update_transaction_time', '0')
                ");
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }
    }

    try {
        $sql = "CREATE TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_ipn_logs(
            id int(11) NOT NULL AUTO_INCREMENT,
            userid int(11) NOT NULL DEFAULT '0' COMMENT 'ID thành viên nếu có',
            log_ip varchar(64) NOT NULL DEFAULT '' COMMENT 'Địa chỉ IP',
            log_data mediumtext NULL DEFAULT NULL COMMENT 'Dữ liệu dạng json_encode',
            request_method varchar(20) NOT NULL DEFAULT '' COMMENT 'Loại truy vấn',
            request_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian log',
            user_agent text NULL DEFAULT NULL,
            PRIMARY KEY (id),
            KEY userid (userid),
            KEY log_ip (log_ip),
            KEY request_method (request_method),
            KEY request_time (request_time)
        ) ENGINE=INNODB";
        $db->query($sql);
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    try {
        $sql = "CREATE TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_admins(
            admin_id mediumint(8) NOT NULL,
            gid smallint(4) NOT NULL,
            add_time int(11) NOT NULL DEFAULT '0',
            update_time int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY (admin_id),
            KEY gid (gid)
        ) ENGINE=INNODB";
        $db->query($sql);
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    try {
        $sql = "CREATE TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_admin_groups(
            gid smallint(4) NOT NULL AUTO_INCREMENT,
            group_title varchar(100) NOT NULL DEFAULT '' COMMENT 'Tên nhóm',
            add_time int(11) NOT NULL DEFAULT '0',
            update_time int(11) NOT NULL DEFAULT '0',
            is_wallet tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem và cập nhật ví tiền',
            is_vtransaction tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem giao dịch',
            is_mtransaction tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem và xử lý giao dịch',
            is_vorder tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem các đơn hàng kết nối',
            is_morder tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem và xử lý các đơn hàng kết nối',
            is_exchange tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền quản lý tỷ giá',
            is_money tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền quản lý tiền tệ',
            is_payport tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền quản lý các cổng thanh toán',
            is_configmod tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền thiết lập cấu hình module',
            is_viewstats tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem thống kê',
            PRIMARY KEY (gid),
            UNIQUE KEY group_title (group_title),
            KEY is_wallet (is_wallet),
            KEY is_vtransaction (is_vtransaction),
            KEY is_mtransaction (is_mtransaction),
            KEY is_vorder (is_vorder),
            KEY is_morder (is_morder),
            KEY is_exchange (is_exchange),
            KEY is_money (is_money),
            KEY is_payport (is_payport),
            KEY is_configmod (is_configmod),
            KEY is_viewstats (is_viewstats)
        ) ENGINE=INNODB";
        $db->query($sql);
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_payment
            ADD active_completed_email TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Kích hoạt gửi email thông báo các giao dịch chưa hoàn thành' AFTER allowedoptionalmoney,
            ADD active_incomplete_email TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Kích hoạt gửi email thông báo các giao dịch đã hoàn thành' AFTER active_completed_email "
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }

    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_transaction
            ADD is_expired TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: Chưa hết hạn, 1: Hết hạn' AFTER tokenkey,
            ADD INDEX is_expired (is_expired) "
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }

    //Thay đổi Enginee của các bảng dữ liệu
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_epay_log ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_exchange ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_exchange_log ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_money ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_money_sys ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_payment ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_payment_discount ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_smslog ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_transaction 
            DROP INDEX customer_name, ADD INDEX customer_name (customer_name(191)) USING BTREE"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_transaction 
            DROP INDEX customer_email, ADD INDEX customer_email (customer_email(191)) USING BTREE"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_transaction ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    try {
        $db->query(
            "ALTER TABLE " . $db_config['prefix'] . "_" . $nv_update_config['formodule'] . "_orders ENGINE = INNODB"
        );
    } catch(PDOException $e) {
        trigger_error($e->getMessage());
    }
    return $return;
}

/**
 * nv_up_f3()
 *
 * @return
 *
 */
function nv_up_f3()
{
    global $nv_update_baseurl, $db, $db_config, $nv_Cache, $array_modlang_update, $nv_update_config;
    $return = array(
        'status' => 1,
        'complete' => 1,
        'next' => 1,
        'link' => 'NO',
        'lang' => 'NO',
        'message' => ''
    );
    foreach ($array_modlang_update as $lang => $array_mod) {
        foreach ($array_mod['mod'] as $module_info) {
            try {
                $db->query("INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (
                    lang, module, config_name, config_value
                ) VALUES 
                    ('" . $lang . "', '" . $module_info['module_data'] . "', 'captcha_type', 'captcha')
                ");
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }
    }
    return $return;
}

/**
 * nv_up_finish()
 *
 * @return
 *
 */
function nv_up_finish()
{
    global $nv_update_baseurl, $db, $db_config, $nv_Cache, $nv_update_config;

    $return = array(
        'status' => 1,
        'complete' => 1,
        'next' => 1,
        'link' => 'NO',
        'lang' => 'NO',
        'message' => ''
    );

    try {
        $num = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_setup_extensions WHERE basename='" . $nv_update_config['formodule'] . "' AND type='module'")->fetchColumn();
        $version = $nv_update_config['to_version'] . " " . $nv_update_config['release_date'];

        if (!$num) {
            $db->query("INSERT INTO " . $db_config['prefix'] . "_setup_extensions (
                id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note
            ) VALUES (
                ". $nv_update_config['id'] .", 'module', '" . $nv_update_config['formodule'] . "', 0, 1,
                '" . $nv_update_config['formodule'] . "', '" . $nv_update_config['formodule'] . "', '" . $version . "', " . NV_CURRENTTIME . ", 
                '". $nv_update_config['author'] ."', '". $nv_update_config['note'] ."'
            )");
        } else {
            $db->query("UPDATE " . $db_config['prefix'] . "_setup_extensions SET
                id=". $nv_update_config['id'] .",
                version='" . $version . "',
                author='". $nv_update_config['author'] ."'
            WHERE basename='" . $nv_update_config['formodule'] . "' AND type='module'");
        }
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    $nv_Cache->delAll(true);

    return $return;
}
