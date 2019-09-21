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

$page_title = $lang_module['permission'];
$module_admin = explode(',', $module_info['admins']);

// Xóa các điều hành viên không có quyền tại module
$is_refresh = false;
foreach ($global_array_admins as $userid_i => $value) {
    if (!in_array($userid_i, $module_admin)) {
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_admins WHERE admin_id = ' . $userid_i);
        $is_refresh = true;
    }
}
// Nếu có thay đổi dữ liệu trong bảng thì load lại
if ($is_refresh) {
    $global_array_admins = nv_organs_array_admins($module_data);
}

if ($nv_Request->isset_request('submit', 'post')) {
    $permission = $nv_Request->get_typed_array('permission', 'post', 'int', []);

    foreach ($permission as $admin_id => $gid) {
        if (isset($global_array_admins[$admin_id]) and (empty($gid) or isset($global_array_admin_groups[$gid]))) {
            $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_admins SET
                gid=" . $gid . ", update_time=" . NV_CURRENTTIME . "
            WHERE admin_id=" . $admin_id;
            $db->query($sql);
        }
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change admin permission', '', $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('LINK_ADMIN_GROUPS', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=permission-groups');
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

if (empty($global_array_admin_groups)) {
    $xtpl->parse('main.no_admin_group');
}

if (empty($global_array_admins)) {
    $xtpl->parse('main.no_admin');
} else {
    // Lấy và xuất admin
    $sql = "SELECT userid, username, first_name, last_name FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN(" . implode(',', array_keys($global_array_admins)) . ")";
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
        $row['add_time'] = $global_array_admins[$row['userid']]['add_time'] ? nv_date('d/m/Y H:i', $global_array_admins[$row['userid']]['add_time']) : '';
        $row['update_time'] = $global_array_admins[$row['userid']]['update_time'] ? nv_date('d/m/Y H:i', $global_array_admins[$row['userid']]['update_time']) : '';
        $xtpl->assign('ROW', $row);

        foreach ($global_array_admin_groups as $pgroup) {
            $pgroup['selected'] = $global_array_admins[$row['userid']]['gid'] == $pgroup['gid'] ? ' selected="selected"' : '';
            $xtpl->assign('GROUP', $pgroup);
            $xtpl->parse('main.data.loop.group');
        }

        $xtpl->parse('main.data.loop');
    }

    $xtpl->parse('main.data');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
