<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['ipnlog1'];

// Xem chi tiết truy vấn
if ($nv_Request->isset_request('viewdetailrequest', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if (empty($id)) {
        nv_htmlOutput('Error ID!');
    }

    $sql = "SELECT log_data FROM " . $db_config['prefix'] . "_" . $module_data . "_ipn_logs WHERE id=" . $id;
    $log_data = $db->query($sql)->fetchColumn();
    if (empty($log_data)) {
        nv_htmlOutput('Error Exists!');
    }

    nv_htmlOutput(nv_htmlspecialchars(print_r(json_decode($log_data, true), true)));
}

// Xóa bỏ
if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_ipn_logs WHERE id=' . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }

    // Xóa
    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_ipn_logs WHERE id=' . $id;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_IPN_LOGS', $id, $admin_info['admin_id']);
    nv_htmlOutput("OK");
}

// Xóa bỏ tất cả
if ($nv_Request->isset_request('deleteall', 'post')) {
    // Xóa hết
    $sql = 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $module_data . '_ipn_logs';
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_ALL_IPN_LOGS', '', $admin_info['admin_id']);
    nv_htmlOutput("OK");
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', ''); // Từ khóa
$array_search['f'] = $nv_Request->get_title('f', 'get', ''); // Từ
$array_search['t'] = $nv_Request->get_title('t', 'get', ''); // Đến

// Xử lý dữ liệu vào
$array_ele_date = ['f', 't'];
foreach ($array_ele_date as $f) {
    $fval = $array_search[$f];
    $array_search[$f] = 0;
    if (preg_match('/^([0-9]{2})\.([0-9]{2})\.([0-9]{4})$/', $fval, $m)) {
        $array_search[$f] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    }
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$where = [];
if (!empty($array_search['q'])) {
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $dblike = $db->dblikeescape($array_search['q']);
    $where[] = "(tb1.log_ip LIKE '%" . $dblike . "%' OR tb1.log_data LIKE '%" . $dblike . "%')";
}
if ($array_search['f']) {
    $base_url .= '&amp;f=' . nv_date('d.m.Y', $array_search['f']);
    $where[] = 'tb1.request_time>=' . $array_search['f'];
}
if ($array_search['t']) {
    $base_url .= '&amp;t=' . nv_date('d.m.Y', $array_search['t']);
    $where[] = 'tb1.request_time<' . ($array_search['t'] + 86400);
}

$db->sqlreset();
$db->select('COUNT(*)');
$db->from($db_config['prefix'] . '_' . $module_data . '_ipn_logs tb1');
$db->join('LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' tb2 ON tb1.userid=tb2.userid');

if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}

$all_page = $db->query($db->sql())->fetchColumn();

$db->order('id DESC');
$db->limit($per_page);
$db->offset(($page - 1) * $per_page);
$db->select('tb1.*, tb2.username');
$result = $db->query($db->sql());

while ($row = $result->fetch()) {
    $row['request_time'] = nv_date('H:i, d/m/Y', $row['request_time']);
    if (empty($row['request_method'])) {
        $row['request_method'] = 'N/A';
    }
    if (empty($row['username'])) {
        $row['username'] = '--';
    }

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if ($generate_page) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$array_search['f'] = empty($array_search['f']) ? '' : nv_date('d.m.Y', $array_search['f']);
$array_search['t'] = empty($array_search['t']) ? '' : nv_date('d.m.Y', $array_search['t']);
$xtpl->assign('DATA_SEARCH', $array_search);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
