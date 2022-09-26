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

$page_title = $lang_module['statistics'];

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA_LINK', NV_BASE_SITEURL . 'themes/default/images/' . $module_file . '/js/');

/**
 * Yêu cầu
 * Thống kê theo tháng trong một năm
 * Theo các năm
 * Tháng của năm này so với tháng của năm trước
 * Hiển thị được: Tổng số tiền nhận được, tổng số tiền khuyến mãi, tổng số tiền chi phí phải trả cho các nhà cung cấp dịch vụ
 */

// Kiểu thống kê
$mode = $nv_Request->get_title('mode', 'get', '');

// Lấy cấu hình mức phí riêng (VNPT EBAY)
$array_payment_discount = array('payment' => 'vnptepay', 'data' => array());

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_payment_discount WHERE payment = ' . $db->quote($array_payment_discount['payment']);
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $key = md5($array_payment_discount['payment'] . $row['revenue_from'] . $row['revenue_to']);

    if (!isset($array_payment_discount['data'][$key])) {
        $array_payment_discount['data'][$key] = array(
            'revenue_from' => floatval($row['revenue_from']),
            'revenue_to' => floatval($row['revenue_to']),
            'provider' => array());
    }

    $array_payment_discount['data'][$key]['provider'][$row['provider']] = floatval($row['discount']);
}
unset($key);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;mode=" . $mode);

if ($mode == 'month') {
    $months = $nv_Request->get_array('month', 'post', array());
    $current_month = nv_date('n', NV_CURRENTTIME);

    for ($i = 1; $i <= 12; $i++) {
        $m = array(
            'key' => $i,
            'title' => $lang_module['statisticsM_title'] . ' ' . str_pad($i, 2, '0', STR_PAD_LEFT),
            'checked' => in_array($i, $months) ? ' checked="checked"' : '',
            'disabled' => $i > $current_month ? ' disabled="disabled"' : '',
            );

        $xtpl->assign('MONTH', $m);
        $xtpl->parse('month.loop_month');
    }

    $error = "";
    $array_data = array(
        'money_total' => 0, // Tiền cập nhật vào tài khoản thành viên
        'money_net' => 0, // Tiền thành viên thực hiện giao dịch
        'money_discount' => 0, // Tiền trả nhà cung cấp dịch vụ
        'money_revenue' => 0 // Tiền nhận được
            );

    if ($nv_Request->isset_request('btnsubmit', 'post')) {
        if (empty($months)) {
            $error = $lang_module['statisticsM_error_select_month'];
        } else {
            $array = array_flip($months);
            $rows = array();

            $i = 0;
            foreach ($array as $month => $v) {
                $rows['label'][$i] = $lang_module['statisticsM_title'] . ' ' . $month;
                $begin_month = mktime(0, 0, 0, $month, 1, date('Y'));
                $end_month = mktime(0, 0, 0, ($month == 12 ? 1 : $month + 1), 1, ($month == 12 ? date('Y') + 1 : date('Y'))) - 1;

                // Thống kê các cổng thanh toán khác
                $sql = 'SELECT SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
				FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
				WHERE transaction_status = 4 AND created_time >= ' . $begin_month . ' AND created_time <= ' . $end_month . '
				AND status = 1 AND payment != ' . $db->quote($array_payment_discount['payment']);

                $result = $db->query($sql);
                $row = $result->fetch();

                $rows['money_total'] = floatval($row['money_total']);
                $rows['money_net'] = floatval($row['money_net']);

                $rows['money_discount'][$i] = floatval($row['money_discount']);
                $rows['money_revenue'][$i] = floatval($row['money_revenue']);

                // Thống kê cổng thanh toán VNPT EBAY
                $sql = 'SELECT provider, SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
				FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
				WHERE transaction_status = 4 AND created_time >= ' . $begin_month . ' AND created_time <= ' . $end_month . '
				AND status = 1 AND payment = ' . $db->quote($array_payment_discount['payment']) . ' GROUP BY provider';
                $result = $db->query($sql);
                $array_row = $result->fetchAll();

                foreach ($array_row as $_row) {
                    $_row['money_total'] = floatval($_row['money_total']);
                    $_row['money_net'] = floatval($_row['money_net']);
                    $_row['money_discount'] = floatval($_row['money_discount']);
                    $_row['money_revenue'] = floatval($_row['money_revenue']);

                    foreach ($array_payment_discount['data'] as $discount) {
                        if ($discount['revenue_from'] <= $_row['money_net'] and $discount['revenue_to'] > $_row['money_net']) {
                            if (isset($discount['provider'][$_row['provider']]) and $discount['provider'][$_row['provider']] > 0) {
                                $_row['money_discount'] = round($_row['money_net'] * $discount['provider'][$_row['provider']] / 100);
                                $_row['money_revenue'] -= $_row['money_discount'];
                            }
                            break;
                        }
                    }

                    $rows['money_total'] += floatval($_row['money_total']);
                    $rows['money_net'] += floatval($_row['money_net']);

                    $rows['money_discount'][$i] += floatval($_row['money_discount']);
                    $rows['money_revenue'][$i] += floatval($_row['money_revenue']);
                }

                $i++;
            }

            $xtpl->assign('MONEY_DISCOUNT', json_encode($rows['money_discount']));
            $xtpl->assign('MONEY_REVENUE', json_encode($rows['money_revenue']));
            $xtpl->assign('LABEL', json_encode($rows['label']));

            $xtpl->assign('CHART_TITLE', sprintf($lang_module['statisticsM_title1'], nv_date('Y')));
            $xtpl->parse('month.result');
        }
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('month.error');
    }

    $xtpl->parse('month');
    $contents = $xtpl->text('month');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} elseif ($mode == 'year') {
    $years = $nv_Request->get_array('year', 'post', array());

    $sql = 'SELECT MAX(created_time) max_year, MIN(created_time) min_year FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction WHERE status = 1';
    $result = $db->query($sql);

    list($max_year, $min_year) = $result->fetch(3);

    if ($min_year) {
        $min_year = date('Y', $min_year);
        $max_year = date('Y', $max_year);

        for ($i = $max_year; $i >= $min_year; $i--) {
            $y = array(
                'key' => $i,
                'title' => $lang_module['statisticsY_title'] . ' ' . $i,
                'checked' => in_array($i, $years) ? ' checked="checked"' : '');

            $xtpl->assign('YEAR', $y);
            $xtpl->parse('year.loop_year');
        }
    }

    $error = "";
    $array_data = array(
        'money_total' => 0,
        'money_net' => 0,
        'money_discount' => 0,
        'money_revenue' => 0);

    if ($nv_Request->isset_request('btnsubmit', 'post')) {
        if (empty($years)) {
            $error = $lang_module['statisticsY_error_select'];
        } else {
            if (sizeof($years) > 1) {
                // Thống kê trên 1 năm
                $array = array_flip($years);
                $rows = array();
                $j = 0;

                foreach ($array as $year => $v) {
                    $rows['label'][$j] = $lang_module['statisticsY_title'] . ' ' . $year;
                    $rows['money_discount'][$j] = 0;
                    $rows['money_revenue'][$j] = 0;

                    // Một năm 12 tháng chia ra 12 vòng lặp
                    for ($i = 1; $i <= 12; $i++) {
                        $begin_month = mktime(0, 0, 0, $i, 1, $year);
                        $end_month = mktime(0, 0, 0, ($i == 12 ? 1 : $i + 1), 1, ($i == 12 ? $year + 1 : $year)) - 1;

                        // Thống kê các cổng thanh toán khác
                        $sql = 'SELECT SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
						FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
						WHERE transaction_status = 4 AND created_time >= ' . $begin_month . ' AND created_time <= ' . $end_month . '
						AND status = 1';
                        $result = $db->query($sql);
                        $row = $result->fetch();

                        $rows['money_total'] = floatval($row['money_total']);
                        $rows['money_net'] = floatval($row['money_net']);

                        $rows['money_discount'][$j] += floatval($row['money_discount']);
                        $rows['money_revenue'][$j] += floatval($row['money_revenue']);

                        // Thống kê cổng thanh toán VNPT EBAY
                        $sql = 'SELECT provider, SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
						FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
						WHERE transaction_status = 4 AND created_time >= ' . $begin_month . ' AND created_time <= ' . $end_month . '
						AND status = 1 AND payment = ' . $db->quote($array_payment_discount['payment']) . ' GROUP BY provider';
                        $result = $db->query($sql);
                        $array_row = $result->fetchAll();

                        foreach ($array_row as $_row) {
                            $_row['money_total'] = floatval($_row['money_total']);
                            $_row['money_net'] = floatval($_row['money_net']);
                            $_row['money_discount'] = floatval($_row['money_discount']);
                            $_row['money_revenue'] = floatval($_row['money_revenue']);

                            foreach ($array_payment_discount['data'] as $discount) {
                                if ($discount['revenue_from'] <= $_row['money_net'] and $discount['revenue_to'] > $_row['money_net']) {
                                    if (isset($discount['provider'][$_row['provider']]) and $discount['provider'][$_row['provider']] > 0) {
                                        $_row['money_discount'] = round($_row['money_net'] * $discount['provider'][$_row['provider']] / 100);
                                        $_row['money_revenue'] -= $_row['money_discount'];
                                    }
                                    break;
                                }
                            }

                            $rows['money_total'] += floatval($_row['money_total']);
                            $rows['money_net'] += floatval($_row['money_net']);

                            $rows['money_discount'][$j] += floatval($_row['money_discount']);
                            $rows['money_revenue'][$j] += floatval($_row['money_revenue']);
                        }
                    }

                    $j++;
                }

                $xtpl->assign('MONEY_DISCOUNT', json_encode($rows['money_discount']));
                $xtpl->assign('MONEY_REVENUE', json_encode($rows['money_revenue']));
                $xtpl->assign('LABEL', json_encode($rows['label']));

                $xtpl->parse('year.result1');
            } else {
                // Thống kê 1 năm
                $array = array_flip($years);

                foreach ($array as $year => $v) {
                    // Một năm 12 tháng chia ra 12 vòng lặp
                    for ($i = 1; $i <= 12; $i++) {
                        $begin_month = mktime(0, 0, 0, $i, 1, $year);
                        $end_month = mktime(0, 0, 0, ($i == 12 ? 1 : $i + 1), 1, ($i == 12 ? $year + 1 : $year)) - 1;

                        // Thống kê các cổng thanh toán khác
                        $sql = 'SELECT SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
						FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
						WHERE transaction_status = 4 AND created_time >= ' . $begin_month . ' AND created_time <= ' . $end_month . '
						AND status = 1 AND payment != ' . $db->quote($array_payment_discount['payment']);
                        $result = $db->query($sql);
                        $row = $result->fetch();

                        $array_data['money_total'] += floatval($row['money_total']);
                        $array_data['money_net'] += floatval($row['money_net']);
                        $array_data['money_discount'] += floatval($row['money_discount']);
                        $array_data['money_revenue'] += floatval($row['money_revenue']);

                        // Thống kê cổng thanh toán VNPT EBAY
                        $sql = 'SELECT provider, SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
						FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
						WHERE transaction_status = 4 AND created_time >= ' . $begin_month . ' AND created_time <= ' . $end_month . '
						AND status = 1 AND payment = ' . $db->quote($array_payment_discount['payment']) . ' GROUP BY provider';
                        $result = $db->query($sql);
                        $array_row = $result->fetchAll();

                        foreach ($array_row as $_row) {
                            $_row['money_total'] = floatval($_row['money_total']);
                            $_row['money_net'] = floatval($_row['money_net']);
                            $_row['money_discount'] = floatval($_row['money_discount']);
                            $_row['money_revenue'] = floatval($_row['money_revenue']);

                            foreach ($array_payment_discount['data'] as $discount) {
                                if ($discount['revenue_from'] <= $_row['money_net'] and $discount['revenue_to'] > $_row['money_net']) {
                                    if (isset($discount['provider'][$_row['provider']]) and $discount['provider'][$_row['provider']] > 0) {
                                        $_row['money_discount'] = round($_row['money_net'] * $discount['provider'][$_row['provider']] / 100);
                                        $_row['money_revenue'] -= $_row['money_discount'];
                                    }
                                    break;
                                }
                            }

                            $array_data['money_total'] += floatval($_row['money_total']);
                            $array_data['money_net'] += floatval($_row['money_net']);
                            $array_data['money_discount'] += floatval($_row['money_discount']);
                            $array_data['money_revenue'] += floatval($_row['money_revenue']);
                        }
                    }
                }

                $rows = array();
                $rows[] = array(
                    'color' => '#EB5749',
                    'value' => $array_data['money_discount'],
                    'label' => $lang_module['num_money_cost'],
                    );
                $rows[] = array(
                    'color' => '#0091F5',
                    'value' => $array_data['money_revenue'],
                    'label' => $lang_module['num_money_collection'],
                    );

                $xtpl->assign('DATAS', json_encode($rows));

                $xtpl->parse('year.result');
            }
        }
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('year.error');
    }

    $xtpl->parse('year');
    $contents = $xtpl->text('year');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} elseif ($mode == 'compare') {
    $month = $nv_Request->get_int('month', 'post', 0);

    $month = ($month != 0) ? $month : nv_date('n', NV_CURRENTTIME);

    $begin_cmonth = mktime(0, 0, 0, $month, 1, nv_date('Y', NV_CURRENTTIME));
    $end_cmonth = mktime(0, 0, 0, ($month == 12 ? 1 : $month + 1), 1, ($month == 12 ? nv_date('Y', NV_CURRENTTIME) + 1 : nv_date('Y', NV_CURRENTTIME))) - 1;

    // Thống kê các cổng thanh toán khác
    $sql = 'SELECT SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
	FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
	WHERE transaction_status = 4 AND created_time >= ' . $begin_cmonth . ' AND created_time <= ' . $end_cmonth . '
	AND status = 1 AND payment != ' . $db->quote($array_payment_discount['payment']);

    $result = $db->query($sql);
    $array_current = $result->fetch();

    // Thống kê cổng thanh toán VNPT EBAY
    $sql = 'SELECT provider, SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
	FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
	WHERE transaction_status = 4 AND created_time >= ' . $begin_cmonth . ' AND created_time <= ' . $end_cmonth . '
	AND status = 1 AND payment = ' . $db->quote($array_payment_discount['payment']) . ' GROUP BY provider';
    $result = $db->query($sql);
    $array_row = $result->fetchAll();

    foreach ($array_row as $_row) {
        $_row['money_total'] = floatval($_row['money_total']);
        $_row['money_net'] = floatval($_row['money_net']);
        $_row['money_discount'] = floatval($_row['money_discount']);
        $_row['money_revenue'] = floatval($_row['money_revenue']);

        foreach ($array_payment_discount['data'] as $discount) {
            if ($discount['revenue_from'] <= $_row['money_net'] and $discount['revenue_to'] > $_row['money_net']) {
                if (isset($discount['provider'][$_row['provider']]) and $discount['provider'][$_row['provider']] > 0) {
                    $_row['money_discount'] = round($_row['money_net'] * $discount['provider'][$_row['provider']] / 100);
                    $_row['money_revenue'] -= $_row['money_discount'];
                }
                break;
            }
        }

        $array_current['money_total'] += floatval($_row['money_total']);
        $array_current['money_net'] += floatval($_row['money_net']);
        $array_current['money_discount'] += floatval($_row['money_discount']);
        $array_current['money_revenue'] += floatval($_row['money_revenue']);
    }

    $rows = array();
    $rows[] = array(
        'color' => '#EB5749',
        'value' => floatval($array_current['money_discount']),
        'label' => $lang_module['num_money_cost'],
    );
    $rows[] = array(
        'color' => '#0091F5',
        'value' => floatval($array_current['money_revenue']),
        'label' => $lang_module['num_money_collection'],
    );
    $xtpl->assign('CURRENT', json_encode($rows));
    if (!($array_current['money_discount'] != 0 or $array_current['money_revenue'] != 0 or $array_current['money_net'] != 0 or $array_current['money_total'] != 0)) {
        $xtpl->assign('NOT_DATA', sprintf($lang_module['not_data'], nv_date('m/Y', $begin_cmonth)));
        $xtpl->parse('compare.notdata');
    }

    $begin_bmonth = mktime(0, 0, 0, $month, 1, date('Y') - 1);
    $end_bmonth = mktime(0, 0, 0, ($month == 12 ? 1 : $month + 1), 1, ($month == 12 ? date('Y') : date('Y') - 1)) - 1;

    // Thống kê các cổng thanh toán khác
    $sql = 'SELECT SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
	FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
	WHERE transaction_status = 4 AND created_time >= ' . $begin_bmonth . ' AND created_time <= ' . $end_bmonth . '
	AND status = 1 AND payment != ' . $db->quote($array_payment_discount['payment']);

    $result = $db->query($sql);
    $array_before = $result->fetch();

    // Thống kê cổng thanh toán VNPT EBAY
    $sql = 'SELECT provider, SUM(money_total) money_total, SUM(money_net) money_net, SUM(money_discount) money_discount, SUM(money_revenue) money_revenue
	FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction
	WHERE transaction_status = 4 AND created_time >= ' . $begin_bmonth . ' AND created_time <= ' . $end_bmonth . '
	AND status = 1 AND payment = ' . $db->quote($array_payment_discount['payment']) . ' GROUP BY provider';
    $result = $db->query($sql);
    $array_row = $result->fetchAll();

    foreach ($array_row as $_row) {
        $_row['money_total'] = floatval($_row['money_total']);
        $_row['money_net'] = floatval($_row['money_net']);
        $_row['money_discount'] = floatval($_row['money_discount']);
        $_row['money_revenue'] = floatval($_row['money_revenue']);

        foreach ($array_payment_discount['data'] as $discount) {
            if ($discount['revenue_from'] <= $_row['money_net'] and $discount['revenue_to'] > $_row['money_net']) {
                if (isset($discount['provider'][$_row['provider']]) and $discount['provider'][$_row['provider']] > 0) {
                    $_row['money_discount'] = round($_row['money_net'] * $discount['provider'][$_row['provider']] / 100);
                    $_row['money_revenue'] -= $_row['money_discount'];
                }
                break;
            }
        }

        $array_before['money_total'] += floatval($_row['money_total']);
        $array_before['money_net'] += floatval($_row['money_net']);
        $array_before['money_discount'] += floatval($_row['money_discount']);
        $array_before['money_revenue'] += floatval($_row['money_revenue']);
    }

    $rows1 = array();
    $rows1[] = array(
        'color' => '#EB5749',
        'value' => floatval($array_before['money_discount']),
        'label' => $lang_module['num_money_cost'],
    );
    $rows1[] = array(
        'color' => '#0091F5',
        'value' => floatval($array_before['money_revenue']),
        'label' => $lang_module['num_money_collection'],
    );

    $xtpl->assign('BEFORE', json_encode($rows1));
    if (!($array_before['money_discount'] != 0 or $array_before['money_revenue'] != 0 or $array_before['money_net'] != 0 or $array_before['money_total'] != 0)) {
        $xtpl->assign('NOT_DATA', sprintf($lang_module['not_data'], nv_date('m/Y', $begin_bmonth)));
        $xtpl->parse('compare.notdata_b');
    }

    $xtpl->assign('CHART_TITLE', sprintf($lang_module['statisticsC_title'], nv_date('m/Y', $begin_cmonth), nv_date('m/Y', $begin_bmonth)));

    for ($a = 1; $a < 12; $a++) {
        $m = array(
            'key' => $a,
            'title' => $lang_module['statisticsM_title'] . ' ' . $a,
            'selected' => $a == $month ? ' selected="selected"' : '');

        $xtpl->assign('MONTH', $m);
        $xtpl->parse('compare.loop_month');
    }

    $xtpl->parse('compare');
    $contents = $xtpl->text('compare');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$xtpl->assign('LINK_MONTH', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;mode=month");
$xtpl->assign('LINK_YEAR', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;mode=year");
$xtpl->assign('LINK_COMPARE', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;mode=compare");

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
