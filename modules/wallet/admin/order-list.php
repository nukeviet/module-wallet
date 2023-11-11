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

$page_title = $lang_module['order_manager'];

// Xóa đơn hàng
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL!!!');
    }

    if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_morder'])) {
        $id = $nv_Request->get_int('id', 'post', 0);

        $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE id=" . $id);
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Del order', $id, $admin_info['userid']);
        nv_htmlOutput('OK');
    }

    nv_htmlOutput('ERROR');
}

// Danh sách các module kết nối đã gọi đơn hàng đến
$sql = "SELECT DISTINCT order_mod FROM " . $db_config['prefix'] . "_" . $module_data . "_orders";
$module_lists = $nv_Cache->db($sql, 'order_mod', $module_name);

$array_search = array();
$array_search['mod'] = $nv_Request->get_title('mod', 'get', '');
$array_search['st'] = $nv_Request->get_int('st', 'get', -1);
$page = $nv_Request->get_int('page', 'get', 1);
if ($page < 1 or $page > 9999999999) {
    $page = 1;
}
$per_page = 15;
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

$where = array();
if (isset($module_lists[$array_search['mod']])) {
    $base_url .= '&amp;mod=' . $array_search['mod'];
    $where[] = "order_mod=" . $db->quote($array_search['mod']);
}
if ($array_search['st'] > -1) {
    $base_url .= '&amp;st=' . $array_search['st'];
    $where[] = "paid_status=" . $array_search['st'];
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

$link_transctions = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=transaction';
$xtpl->assign('VIEW_TRANSCTION_NOTE', sprintf($lang_module['order_update_status_note'], $link_transctions));

$db->sqlreset()->select('COUNT(*)')->from($db_config['prefix'] . "_" . $module_data . "_orders");
if ($where) {
    $db->where(implode(' AND ', $where));
}
$all_page = $db->query($db->sql())->fetchColumn();

$db->select('*')->order('add_time DESC')->limit($per_page)->offset(($page - 1) * $per_page);
$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    $row['code'] = sprintf('DH%010s', $row['id']);
    $row['module_title'] = isset($sys_mods[$row['order_mod']]) ? $sys_mods[$row['order_mod']]['custom_title'] : $row['order_mod'];
    $row['module_link'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;mod=" . $row['order_mod'];
    $row['order_object'] = nv_ucfirst($row['order_object']);
    $row['money_amount'] = get_display_money($row['money_amount']);
    $row['add_time'] = nv_date('d/m/Y H:i', $row['add_time']);
    $row['update_time'] = !empty($row['update_time']) ? nv_date('d/m/Y H:i', $row['update_time']) : '';
    $row['paid_status'] = isset($global_array_transaction_status[$row['paid_status']]) ? $global_array_transaction_status[$row['paid_status']] : 'N/A';

    $xtpl->assign('ROW', $row);

    // Link đến chi tiết đơn hàng trong admin
    if (empty($row['url_admin'])) {
        $xtpl->parse('main.loop.obj_text');
    } else {
        $row['url_admin'] = unserialize($row['url_admin']);
        $link_obj = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $row['order_mod'] . '&amp;' . NV_OP_VARIABLE . '=' . $row['url_admin']['op'];
        if (!empty($row['url_admin']['querystr'])) {
            $link_obj .= '&amp;' . $row['url_admin']['querystr'];
        }
        $xtpl->assign('LINK_OBJ', $link_obj);
        $xtpl->parse('main.loop.obj_link');
    }

    if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_morder'])) {
        $xtpl->parse('main.loop.delete');
    }

    $xtpl->parse('main.loop');
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if ($generate_page) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

foreach ($module_lists as $mod) {
    $mod['selected'] = $array_search['mod'] == $mod['order_mod'] ? ' selected="selected"' : '';
    $mod['title'] = isset($sys_mods[$mod['order_mod']]) ? $sys_mods[$mod['order_mod']]['custom_title'] : $mod['order_mod'];
    $mod['key'] = $mod['order_mod'];
    $xtpl->assign('MOD', $mod);
    $xtpl->parse('main.mod');
}

foreach ($global_array_transaction_status as $key => $value) {
    $transtatus = array(
        'key' => $key,
        'title' => $value,
        'selected' => $array_search['st'] == $key ? ' selected="selected"' : ''
    );
    $xtpl->assign('TRANSTATUS', $transtatus);
    $xtpl->parse('main.transtatus');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
