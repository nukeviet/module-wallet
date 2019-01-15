<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET')) {
    die('Stop!!!');
}

/*
 * Quy trình thanh toán thủ công:
 * - Người dùng chọn hình thức nạp, thanh toán
 * - Người dùng xem hướng dẫn và ấn nút tiếp tục
 * - Người dùng điền thông tin (nếu nạp tiền)
 * - Thông báo để người dùng đến để nộp tiền và kết thúc
 * - Tự quay về phần lịch sử giao dịch
 */

$checksum_str = $post['transaction_code'] . $post['money_net'] . $post['money_unit'] . $post['transaction_info'] . $post['tokenkey'];
$checksum = hash('sha256', $checksum_str);

// Tạo URL để chuyển ngay về phần complete
$url = $post['ReturnURL'];
$url .= '&code=' . $post['transaction_code'] . '&money=' . $post['money_net'] . '&unit=' . $post['money_unit'] . '&info=' . urlencode($post['transaction_info']) . '&checksum=' . $checksum;
