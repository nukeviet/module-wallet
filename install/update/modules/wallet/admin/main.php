<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$page_title = $table_caption = $lang_module['acountuser'];

// Kiểm tra quyền quản lý ví tiền, nếu không có quyền chuyển đến quyền gần nhất có thể
if (!$IS_FULL_ADMIN and empty($PERMISSION_ADMIN['is_wallet'])) {
    $show_funcs = [
        'transaction',
        'order-list',
        'exchange',
        'historyexchange',
        'money',
        'payport',
        'config',
        'statistics'
    ];
    foreach ($allow_func as $op) {
        if (in_array($op, $show_funcs)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        }
    }

    $contents = nv_theme_alert($lang_module['permission_none'], $lang_module['permission_none_explain'], 'danger');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$page = $nv_Request->get_int('page', 'get', 1);
if ($page < 1 or $page > 9999999999) {
    $page = 1;
}
$per_page = 30;
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

$methods = array(
    'userid' => array(
        'key' => 'userid',
        'value' => $lang_module['search_id'],
        'selected' => ''
    ),
    'username' => array(
        'key' => 'username',
        'value' => $lang_module['search_account'],
        'selected' => ''
    ),
    'first_name' => array(
        'key' => 'first_name',
        'value' => $lang_module['search_name'],
        'selected' => ''
    ),
    'email' => array(
        'key' => 'email',
        'value' => $lang_module['search_mail'],
        'selected' => ''
    )
);

$array_search = array();
$array_search['q'] = $nv_Request->get_title('q', 'get', '');
$array_search['f'] = $nv_Request->get_title('f', 'get', '');
$isSearch = false;

$db->sqlreset();
$db->select('COUNT(*)');
$db->from($db_config['prefix'] . "_" . $module_data . "_money tb1, " . NV_USERS_GLOBALTABLE . " tb2");

$where = array('tb1.userid=tb2.userid');
if (!empty($array_search['q'])) {
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $dbkey = $db->dblikeescape($array_search['q']);
    $array_likes = array(
        'userid' => "tb2.userid LIKE '" . $dbkey . "'",
        'username' => "tb2.username LIKE '%" . $dbkey . "%'",
        'first_name' => ($global_config['name_show'] == 0 ? "concat(last_name,' ',first_name)" : "concat(first_name,' ',last_name)") . " LIKE '%" . $dbkey . "%'",
        'email' => "tb2.email LIKE '%" . $dbkey . "%'"
    );
    if (isset($methods[$array_search['f']])) {
        $query_q = $array_likes[$array_search['f']];
    } else {
        $query_q = '(' . implode(' OR ', $array_likes) . ')';
    }
    $where[] = $query_q;
    $isSearch = true;
}
if (isset($methods[$array_search['f']])) {
    $base_url .= '&amp;f=' . $array_search['f'];
}

$db->where(implode(' AND ', $where));

$result = $db->query($db->sql());
$all_page = $result->fetchColumn();

$db->select('tb1.*, tb2.username, tb2.email, tb2.first_name, tb2.last_name');
$db->order('tb1.created_time DESC');
$db->limit($per_page);
$db->offset(($page - 1) * $per_page);

$result = $db->query($db->sql());

$array = $array_users = $array_userids = array();
while ($row = $result->fetch()) {
    $array[$row['userid'] . $row['money_unit']] = $row;
    if (!empty($row['created_userid'])) {
        $array_userids[$row['created_userid']] = $row['created_userid'];
    }
}

if (!empty($array_userids)) {
    $sql = "SELECT userid, username, first_name, last_name FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN(" . implode(',', $array_userids) . ")";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $array_users[$row['userid']] = $row;
    }
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
$xtpl->assign('SORTURL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
$xtpl->assign('TABLE_CAPTION', $table_caption);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('SEARCH', $array_search);

// Xác định cập nhật hay tạo mới tài khoản
$info_userdata = array();
$username = $nv_Request->get_title('u', 'get', '');
if (!empty($username)) {
    $sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE username=:username";
    $sth = $db->prepare($sql);
    $sth->bindParam(':username', $username, PDO::PARAM_STR);
    $sth->execute();
    $info_userdata = $sth->fetch();
    if (empty($info_userdata)) {
        nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
    }
}
$update = $nv_Request->get_int('update', 'get', 0);

$isShowAccountArea = false;

// Add new acount
if (!empty($info_userdata)) {
    $money_unit = $nv_Request->get_title('money_unit', 'get', '');
    if (!empty($money_unit)) {
        $sql = "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_money WHERE userid ='" . $info_userdata['userid'] . "' AND money_unit=" . $db->quote($money_unit);
        if ($db->query($sql)->fetchColumn() != 1) {
            nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
        }
    }

    foreach ($global_array_money_sys as $arr_money_sys_i) {
        if (empty($money_unit) or $money_unit == $arr_money_sys_i['code']) {
            $sl = ($money_unit == $arr_money_sys_i['code']) ? "selected=\"selected\"" : "";
            $xtpl->assign('select_money_sys', $sl);
            $xtpl->assign('moneysys', $arr_money_sys_i['code']);
            $xtpl->parse('main.createacount.loopmoney');
        }
    }

    unset($global_array_transaction_type['4']);
    if (!$update) {
        unset($global_array_transaction_type['1'], $global_array_transaction_type['2']);
        $transaction_type = 0;
    } else {
        $transaction_type = 1;
    }
    foreach ($global_array_transaction_type as $key => $value) {
        $sl = ($key == $transaction_type) ? " selected=\"selected\"" : "";
        $xtpl->assign('OPTION', array('key' => $key, 'title' => $value, 'selected' => $sl));
        $xtpl->parse('main.createacount.transaction_type');
    }

    // Nếu là cập nhật tài khoản thì hiển thị tùy chọn trừ tiền
    if ($update) {
        $xtpl->parse('main.createacount.subtype');
        $xtpl->assign('CAPTION', $lang_module['editacount']);
    } else {
        $xtpl->assign('CAPTION', $lang_module['creataccount']);
    }

    $xtpl->assign('USERNAME', $info_userdata['username']);
    $xtpl->assign('USERID', $info_userdata['userid']);

    $xtpl->parse('main.createacount');
}

foreach ($methods as $m) {
    $m['selected'] = $m['key'] == $array_search['f'] ? ' selected="selected"' : '';
    $xtpl->assign('METHODS', $m);
    $xtpl->parse('main.method');
}

if (!empty($array)) {
    foreach ($array as $row) {
        $row['edit_url'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;u=" . urlencode($row['username']) . "&amp;money_unit=" . $row['money_unit'] . "&update=1";
        $row['view_url'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=transaction&amp;userid=" . $row['userid'];
        $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
        $row['created_time'] = nv_date('d/m/Y, H:i', $row['created_time']);
        if (isset($array_users[$row['created_userid']])) {
            $row['created_userid'] = $array_users[$row['created_userid']]['username'];
        } else {
            $row['created_userid'] = $lang_module['addacountsys'];
        }
        $row['money_total'] = get_display_money($row['money_total']);
        $xtpl->assign('ACOUNT', $row);
        $xtpl->parse('main.listacount.loop_listacount');
    }
    if ($generate_page) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.listacount.generate_page');
    }
    $xtpl->parse('main.listacount');
} elseif (empty($info_userdata) and !$isSearch) {
    $xtpl->parse('main.noacount');
    $isShowAccountArea = true;
}

if ($isShowAccountArea) {
    $xtpl->assign('COLLAPSE_ACC1', 'true');
    $xtpl->assign('COLLAPSE_ACC2', ' in');
} else {
    $xtpl->assign('COLLAPSE_ACC1', 'false');
    $xtpl->assign('COLLAPSE_ACC2', '');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
