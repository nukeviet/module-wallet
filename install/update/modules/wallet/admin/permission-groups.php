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

$set_active_op = 'permission';
$page_title = $lang_module['permission_group'];

// Xóa nhóm quyền admin
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL!!!');
    }

    $id = $nv_Request->get_int('id', 'post', 0);

    $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_admin_groups WHERE gid=" . $id);
    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Del permission group', $id, $admin_info['userid']);
    nv_htmlOutput('OK');
}

$id = $nv_Request->get_int('id', 'get', 0);
if (!empty($id)) {
    if (!isset($global_array_admin_groups[$id])) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
    $array = $global_array_admin_groups[$id];
    $form_caption = $lang_module['permission_group_edit'];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $array = [
        'group_title' => '',
        'is_wallet' => 0,
        'is_vtransaction' => 0,
        'is_mtransaction' => 0,
        'is_vorder' => 0,
        'is_morder' => 0,
        'is_exchange' => 0,
        'is_money' => 0,
        'is_payport' => 0,
        'is_configmod' => 0,
        'is_viewstats' => 0
    ];
    $cid = $nv_Request->get_int('cid', 'get', 0);
    if (isset($global_array_admin_groups[$cid])) {
        $array = $global_array_admin_groups[$cid];
    }
    $form_caption = $lang_module['permission_group_add'];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}
$error = '';
$is_submit = false;

if ($nv_Request->isset_request('btnsubmit', 'post')) {
    $is_submit = true;
    $array['group_title'] = nv_substr($nv_Request->get_title('group_title', 'post', ''), 0, 100);
    $array['is_wallet'] = intval($nv_Request->get_bool('is_wallet', 'post', false));
    $array['is_vtransaction'] = intval($nv_Request->get_bool('is_vtransaction', 'post', false));
    $array['is_mtransaction'] = intval($nv_Request->get_bool('is_mtransaction', 'post', false));
    $array['is_vorder'] = intval($nv_Request->get_bool('is_vorder', 'post', false));
    $array['is_morder'] = intval($nv_Request->get_bool('is_morder', 'post', false));
    $array['is_exchange'] = intval($nv_Request->get_bool('is_exchange', 'post', false));
    $array['is_money'] = intval($nv_Request->get_bool('is_money', 'post', false));
    $array['is_payport'] = intval($nv_Request->get_bool('is_payport', 'post', false));
    $array['is_configmod'] = intval($nv_Request->get_bool('is_configmod', 'post', false));
    $array['is_viewstats'] = intval($nv_Request->get_bool('is_viewstats', 'post', false));

    // Kiểm tra trùng
    $sql = "SELECT COUNT(gid) FROM " . $db_config['prefix'] . "_" . $module_data . "_admin_groups WHERE group_title=" . $db->quote($array['group_title']);
    if ($id) {
        $sql .= " AND gid!=" . $id;
    }
    $is_exists = $db->query($sql)->fetchColumn();

    if (empty($array['group_title'])) {
        $error = $lang_module['permission_error_title'];
    } elseif ($is_exists) {
        $error = $lang_module['permission_error_exists'];
    } else {
        if ($id) {
            $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_admin_groups SET
                group_title=" . $db->quote($array['group_title']) . ",
                is_wallet=" . $array['is_wallet'] . ",
                is_vtransaction=" . $array['is_vtransaction'] . ",
                is_mtransaction=" . $array['is_mtransaction'] . ",
                is_vorder=" . $array['is_vorder'] . ",
                is_morder=" . $array['is_morder'] . ",
                is_exchange=" . $array['is_exchange'] . ",
                is_money=" . $array['is_money'] . ",
                is_payport=" . $array['is_payport'] . ",
                is_configmod=" . $array['is_configmod'] . ",
                is_viewstats=" . $array['is_viewstats'] . ",
                update_time=" . NV_CURRENTTIME . "
            WHERE gid=" . $id;
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Update permission group', 'ID: ' . $id, $admin_info['userid']);
        } else {
            $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_admin_groups (
                group_title, add_time, is_wallet, is_vtransaction, is_mtransaction, is_vorder,
                is_morder, is_exchange, is_money, is_payport, is_configmod, is_viewstats
            ) VALUES (
                " . $db->quote($array['group_title']) . ", " . NV_CURRENTTIME . ",
                " . $array['is_wallet'] . ",
                " . $array['is_vtransaction'] . ",
                " . $array['is_mtransaction'] . ",
                " . $array['is_vorder'] . ",
                " . $array['is_morder'] . ",
                " . $array['is_exchange'] . ",
                " . $array['is_money'] . ",
                " . $array['is_payport'] . ",
                " . $array['is_configmod'] . ",
                " . $array['is_viewstats'] . "
            )";
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Add permission group', $array['group_title'], $admin_info['userid']);
        }

        $db->query($sql);
        $nv_Cache->delMod($module_name);

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_CAPTION', $form_caption);
$xtpl->assign('FORM_ACTION', $form_action);

$array['is_wallet'] = empty($array['is_wallet']) ? '' : ' checked="checked"';
$array['is_vtransaction'] = empty($array['is_vtransaction']) ? '' : ' checked="checked"';
$array['is_mtransaction'] = empty($array['is_mtransaction']) ? '' : ' checked="checked"';
$array['is_vorder'] = empty($array['is_vorder']) ? '' : ' checked="checked"';
$array['is_morder'] = empty($array['is_morder']) ? '' : ' checked="checked"';
$array['is_exchange'] = empty($array['is_exchange']) ? '' : ' checked="checked"';
$array['is_money'] = empty($array['is_money']) ? '' : ' checked="checked"';
$array['is_payport'] = empty($array['is_payport']) ? '' : ' checked="checked"';
$array['is_configmod'] = empty($array['is_configmod']) ? '' : ' checked="checked"';
$array['is_viewstats'] = empty($array['is_viewstats']) ? '' : ' checked="checked"';

$xtpl->assign('DATA', $array);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if ($is_submit or $id) {
    $xtpl->parse('main.scrolltop');
}

// Danh sách các nhóm đối tượng
if (!empty($global_array_admin_groups)) {
    foreach ($global_array_admin_groups as $row) {
        $row['add_time'] = $row['add_time'] ? nv_date('d/m/Y H:i', $row['add_time']) : '';
        $row['update_time'] = $row['update_time'] ? nv_date('d/m/Y H:i', $row['update_time']) : '';
        $row['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $row['gid'];
        $row['link_copy'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;cid=' . $row['gid'];
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.list.loop');
    }
    $xtpl->parse('main.list');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
