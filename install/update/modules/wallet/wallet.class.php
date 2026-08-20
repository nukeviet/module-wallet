<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

global $db_config;

//Wallet name
define('NV_WALLET_MODULE', "wallet");

// Wallet table
define('NV_WALLET_TABLE', $db_config['prefix'] . "_" . NV_WALLET_MODULE);

// Wallet admin url
define('NV_WALLET_URL_OP', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . NV_WALLET_MODULE . "&amp;" . NV_OP_VARIABLE);
define('NV_WALLET_URL_OP_HEADER', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . NV_WALLET_MODULE . "&" . NV_OP_VARIABLE);

// Wallet site url
define('NV_WALLET_SURL_OP', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . NV_WALLET_MODULE . "&amp;" . NV_OP_VARIABLE);
define('NV_WALLET_SURL_OP_HEADER', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . NV_WALLET_MODULE . "&" . NV_OP_VARIABLE);

class nukeviet_wallet
{
    private $language = array();
    private $glanguage = array();
    private $db = null;
    private $nv_Cache = null;
    private $is_error = false;

    /**
     * nukeviet_wallet::__construct()
     *
     * @return
     */
    public function __construct()
    {
        global $lang_global, $db, $nv_Cache;

        $lang_module = array();
        $lang_file = NV_ROOTDIR . "/modules/wallet/language/" . NV_LANG_DATA . ".php";
        if (file_exists($lang_file)) {
            require_once $lang_file;
        } else {
            require_once NV_ROOTDIR . "/modules/wallet/language/vi.php";
        }

        $this->language = $lang_module;
        $this->glanguage = $lang_global;
        $this->db = $db;
        $this->nv_Cache = $nv_Cache;
    }

    /**
     * nukeviet_wallet::lang()
     *
     * @param string $key
     * @return
     */
    public function lang($key = '')
    {
        $numargs = func_num_args();
        if ($numargs < 1) {
            return '';
        } elseif ($numargs == 1) {
            return isset($this->language[$key]) ? $this->language[$key] : $key;
        }
        if (!isset($this->language[$key])) {
            return $key;
        }
        $arg_list = func_get_args();
        unset($arg_list[0]);
        return vsprintf($this->language[$key], $arg_list);
    }

    /**
     * nukeviet_wallet::glang()
     *
     * @param mixed $key
     * @return
     */
    public function glang($key)
    {
        return isset($this->glanguage[$key]) ? $this->glanguage[$key] : $key;
    }

    /**
     * nukeviet_wallet::list_money()
     *
     * @param string $select_money
     * @return
     */
    public function list_money($select_money = "")
    {
        $sql = "SELECT * FROM " . NV_WALLET_TABLE . "_money_sys";
        $result = $this->nv_Cache->db($sql, 'code', NV_WALLET_MODULE);

        $array = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $array[$row['code']] = array(
                    "id" => $row['id'],
                    "code" => $row['code'],
                    "selected" => ($row['code'] == $select_money) ? " selected=\"selected\"" : "",
                    "currency" => $row['currency']
                );
            }
        }

        return $array;
    }

    /**
     * nukeviet_wallet::all_exchange()
     *
     * @param bool $is_array
     * @return
     */
    public function all_exchange($is_array = false)
    {
        $sql = "SELECT money_unit, than_unit FROM " . NV_WALLET_TABLE . "_exchange WHERE status=1";
        $result = $this->nv_Cache->db($sql, 'id', NV_WALLET_MODULE);

        $array = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $array[] = $row['money_unit'] . "&nbsp;=&gt;&nbsp;" . $row['than_unit'];
            }
        }

        if (!$is_array) {
            $array = implode(", ", $array);
        }

        return $array;
    }

    /**
     * nukeviet_wallet::init()
     *
     * @param mixed $userid
     * @return
     */
    private function init($userid)
    {
        $wallet_setting = array();
        $wallet_setting['money_unit'] = "VND";

        if (empty($userid)) {
            return array();
        }

        $_sql = 'SELECT * FROM ' . NV_WALLET_TABLE . '_money WHERE userid=' . $userid;
        $_query = $this->db->query($_sql);

        if (!$row = $_query->fetch()) {
            $sql = "INSERT INTO " . NV_WALLET_TABLE . "_money (userid, created_time, created_userid, status, money_unit, money_in, money_out, money_total, note, tokenkey) VALUES ( " . $userid . ", " . NV_CURRENTTIME . ", 0, 1, " . $this->db->quote($wallet_setting['money_unit']) . ", 0, 0, 0, '', '' )";
            $this->db->query($sql);
        }

        $array = array(
            "userid" => $userid,
            "created_time" => date("d/m/Y", NV_CURRENTTIME),
            "created_userid" => 0,
            "status" => 1,
            "money_unit" => $wallet_setting['money_unit'],
            "money_in" => 0,
            "money_out" => 0,
            "money_total" => 0,
            "money_current" => 0,
            "note" => ""
        );

        $this->nv_Cache->delMod(NV_WALLET_MODULE);

        return $array;
    }

    /**
     * @param mixed $userid
     * @return
     */
    public function my_money($userid)
    {
        if (empty($userid)) {
            return [];
        }

        $wallet_setting = [];
        $wallet_setting['money_unit'] = "VND";

        $sql = "SELECT * FROM " . NV_WALLET_TABLE . "_money WHERE userid=" . $userid . " AND money_unit=" . $this->db->quote($wallet_setting['money_unit']);
        $row = $this->db->query($sql)->fetch();
        if (!empty($row)) {
            $array = [
                'userid' => $row['userid'],
                'created_time' => date('d/m/Y', $row['created_time']),
                'created_userid' => $row['created_userid'],
                'status' => $row['status'],
                'money_unit' => $row['money_unit'],
                'money_in' => number_format($row['money_in'], 0, '.', ' '),
                'money_out' => number_format($row['money_out'], 0, '.', ' '),
                'money_total' => number_format($row['money_total'], 0, '.', ' '),
                'money_current' => (int) $row['money_total'],
                'note' => $row['note']
            ];
        } else {
            $array = $this->init($userid);
        }

        return $array;
    }

    /**
     * nukeviet_wallet::exchange()
     *
     * @param mixed $money
     * @param mixed $money_unit
     * @param mixed $than_unit
     * @return
     */
    public function exchange($money, $money_unit, $than_unit)
    {
        if ($money_unit == $than_unit)
            return $money;
        if ($money == 0)
            return 0;

        $sql = "SELECT id, exchange FROM " . NV_WALLET_TABLE . "_exchange WHERE status=1 AND money_unit='" . $money_unit . "' AND than_unit='" . $than_unit . "'";
        $list = $this->nv_Cache->db($sql, 'id', NV_WALLET_MODULE);

        if (!empty($list)) {
            foreach ($list as $row) {
                return ($money * $row['exchange']);
            }
        } else {
            return false;
        }
    }

    /**
     * nukeviet_wallet::update()
     *
     * @param mixed $money: Số tiền cộng hoặc trừ
     * @param mixed $money_unit: Loại tiền
     * @param mixed $userid: ID tài khoản bị tác động
     * @param string $message: Thông tin giao dịch
     * @param bool $is_add: Công hay trừ, true là cộng còn lại là trừ
     * @param integer $dayamount
     * @return
     */
    public function update($money, $money_unit, $userid, $message = "", $is_add = false, $dayamount = 0)
    {
        $this->is_error = false;

        // Nếu có số tiền thì trừ hoặc cộng
        $money = floatval($money);
        if ($money <= 0) {
            $this->is_error = true;
            return $this->lang('payclass_error_money');
        }
        $userid = abs(intval($userid));
        $tran_status = ($is_add ? 1 : -1);
        $money_sys = $this->list_money();
        if (!isset($money_sys[$money_unit])) {
            $this->is_error = true;
            return $this->lang('payclass_error_money_unit');
        }

        // Lưu thông tin giao dịch trước
        $sql = "INSERT INTO " . NV_WALLET_TABLE . "_transaction (
            created_time, status, money_unit, money_total, money_net, money_discount, money_revenue,
            userid, adminid, customer_id, customer_name, customer_email, customer_phone, customer_address, customer_info,
            transaction_id, transaction_type, transaction_status, transaction_time, transaction_info, transaction_data,
            payment, provider, tokenkey
        ) VALUES (
            " . NV_CURRENTTIME . ", " . $tran_status . ", :money_unit, :money_total, :money_net, 0, :money_revenue,
            :userid, 0, :customer_id, '', '', '', '', '', '', -1, 4,
            " . NV_CURRENTTIME . ", :transaction_info, '', '', '', ''
        )";
        $data_insert = array();
        $data_insert['money_unit'] = $money_unit;
        $data_insert['money_total'] = $money;
        $data_insert['money_net'] = $money;
        $data_insert['money_revenue'] = $money;
        $data_insert['userid'] = $userid;
        $data_insert['customer_id'] = $userid;
        $data_insert['transaction_info'] = strval($message);

        $tran_id = $this->db->insert_id($sql, 'id', $data_insert);
        if (!$tran_id) {
            $this->is_error = true;
            return $this->lang('payclass_error_save_transaction');
        }

        if (!$is_add) {
            // Trừ tiền
            $sql = "UPDATE " . NV_WALLET_TABLE . "_money SET
                money_out = money_out + " . $money . ",
                money_total = money_total - " . $money . "
            WHERE userid=" . $userid . " AND money_unit=" . $this->db->quote($money_unit);
            if (!$this->db->exec($sql)) {
                $this->is_error = true;
                return $this->lang('payclass_error_update_account');
            }
        } else {
            // Cộng tiền
            $sql = "UPDATE " . NV_WALLET_TABLE . "_money SET
                money_in = money_in + " . $money . ",
                money_total = money_total + " . $money . "
            WHERE userid=" . $userid . " AND money_unit=" . $this->db->quote($money_unit);
            if (!$this->db->exec($sql)) {
                $this->is_error = true;
                return $this->lang('payclass_error_update_account');
            }
        }

        $this->nv_Cache->delMod(NV_WALLET_MODULE);
        return $tran_id;
    }

    /**
     * nukeviet_wallet::getInfoPayment()
     *
     * @param mixed $data
     * @return
     */
    public function getInfoPayment($data)
    {
        $return = array(
            'status' => 'ERROR',
            'message' => '',
            'url' => ''
        );
        $check = $this->verifyPaymentData($data);
        if ($check !== true) {
            return $check;
        }

        $order = $this->getOrder($data['modname'], $data['id']);
        if (empty($order)) {
            // Xác định $secret_code mới
            $secret_code = $this->getOrderUniqueSecretcode();
            $sql = "INSERT INTO " . NV_WALLET_TABLE . "_orders (
                order_mod, order_id, order_message, order_object, order_name, money_amount, money_unit, secret_code, url_back,
                url_admin, add_time, update_time, paid_status, paid_time
            ) VALUES (
                :order_mod, :order_id, '', :order_object, :order_name, :money_amount, :money_unit, :secret_code, :url_back, :url_admin, " . NV_CURRENTTIME . ", 0, 0, 0
            )";
            try {
                $url_back = array();
                $url_back['op'] = $data['url_back']['op'];
                $url_back['querystr'] = !empty($data['url_back']['querystr']) ? $data['url_back']['querystr'] : '';
                $url_back = serialize($url_back);

                $url_admin = array();
                $url_admin['op'] = '';
                $url_admin['querystr'] = '';
                if (!empty($data['url_admin']) and is_array($data['url_admin'])) {
                    if (!empty($data['url_admin']['op'])) {
                        $url_admin['op'] = $data['url_admin']['op'];
                    }
                    if (!empty($data['url_admin']['querystr'])) {
                        $url_admin['querystr'] = $data['url_admin']['querystr'];
                    }
                }
                $url_admin = serialize($url_admin);
                $order_object = !empty($data['order_object']) ? ((string)$data['order_object']) : '';
                $order_name = !empty($data['order_name']) ? ((string)$data['order_name']) : '';

                $sth = $this->db->prepare($sql);
                $sth->bindParam(':order_mod', $data['modname'], PDO::PARAM_STR);
                $sth->bindParam(':order_id', $data['id'], PDO::PARAM_STR);
                $sth->bindParam(':order_object', $order_object, PDO::PARAM_STR);
                $sth->bindParam(':order_name', $order_name, PDO::PARAM_STR);
                $sth->bindParam(':money_amount', $data['money_amount'], PDO::PARAM_STR);
                $sth->bindParam(':money_unit', $data['money_unit'], PDO::PARAM_STR);
                $sth->bindParam(':secret_code', $secret_code, PDO::PARAM_STR);
                $sth->bindParam(':url_back', $url_back, PDO::PARAM_STR, strlen($url_back));
                $sth->bindParam(':url_admin', $url_admin, PDO::PARAM_STR, strlen($url_admin));
                $sth->execute();
            } catch (Exception $e) {
                $return['message'] = $this->lang('paygate_error_saveorders');
                return $return;
            }
            $order = $this->getOrder($data['modname'], $data['id']);
        }
        if (empty($order)) {
            $return['message'] = $this->lang('paygate_error_saveorders');
            return $return;
        }

        $return['status'] = 'SUCCESS';
        $return['url'] = $this->getOrderPayUrl($order);

        return $return;
    }

    /**
     * nukeviet_wallet::checkInfoPayment()
     *
     * @param mixed $data
     * @return
     */
    public function checkInfoPayment($data)
    {
        $return = array(
            'status' => 'ERROR',
            'message' => '',
            'data' => array()
        );
        $check = $this->verifyPaymentData($data, false);
        if ($check !== true) {
            return $check;
        }

        $order = $this->getOrder($data['modname'], $data['id']);

        if (empty($order)) {
            $return['message'] = $this->lang('paygate_error_order');
            return $return;
        }

        $return['status'] = 'SUCCESS';
        $return['data'] = array($order['paid_status'], $order['paid_time'], $order['paid_id']);

        return $return;
    }

    public function resetPayment($data)
    {
        $return = [
            'status' => 'ERROR',
            'message' => '',
            'data' => []
        ];
        $check = $this->verifyPaymentData($data, false);
        if ($check !== true) {
            return $check;
        }

        $order = $this->getOrder($data['modname'], $data['id']);

        if (empty($order)) {
            $return['message'] = $this->lang('paygate_error_order');
            return $return;
        }

        // Không reset thanh toán của đơn hàng đã hoàn tất
        if ($order['paid_status'] == 4) {
            $return['message'] = $this->lang('paygate_error_resetsuccess');
            return $return;
        }

        // Cập nhật lại trạng thái thanh toán về 0 để tiếp tục thanh toán lại
        $sql = "UPDATE " . NV_WALLET_TABLE . "_orders SET
            update_time = " . NV_CURRENTTIME . ",
            paid_status = '0',
            paid_id = '0',
            paid_time = 0
        WHERE id=" . $order['id'];
        if (!$this->db->exec($sql)) {
            $return['message'] = $this->lang('paygate_error_reset');
            return $return;
        }

        $return['status'] = 'SUCCESS';

        return $return;
    }

    /**
     * nukeviet_wallet::verifyPaymentData()
     *
     * @param mixed $data
     * @param bool $fullVerify
     * @return
     */
    private function verifyPaymentData($data, $fullVerify = true)
    {
        global $site_mods;

        $return = array(
            'status' => 'ERROR',
            'message' => '',
            'url' => ''
        );

        // Các bước kiểm tra dữ liệu đầu vào
        if (empty($data) or !is_array($data)) {
            $return['message'] = $this->lang('paygate_error_inputdata');
            return $return;
        }
        if (empty($data['modname']) or !isset($site_mods[$data['modname']])) {
            $return['message'] = $this->lang('paygate_error_modname');
            return $return;
        }
        if (empty($data['id'])) {
            $return['message'] = $this->lang('paygate_error_id');
            return $return;
        }
        if (strlen($data['id']) > 100) {
            $return['message'] = $this->lang('paygate_error_id1');
            return $return;
        }
        if ($fullVerify) {
            if (empty($data['money_amount']) or $data['money_amount'] <= 0) {
                $return['message'] = $this->lang('paygate_error_money_amount');
                return $return;
            }
            if (empty($data['money_unit'])) {
                $return['message'] = $this->lang('paygate_error_money_unit');
                return $return;
            }

            $money_sys = $this->list_money();
            if (!isset($money_sys[$data['money_unit']])) {
                $return['message'] = $this->lang('paygate_error_money_unit1', $data['money_unit']);
                return $return;
            }

            if (empty($data['url_back']) or !is_array($data['url_back']) or empty($data['url_back']['op'])) {
                $return['message'] = $this->lang('paygate_error_urlback');
                return $return;
            }
        }

        return true;
    }

    /**
     * nukeviet_wallet::getOrder()
     *
     * @param mixed $modname
     * @param mixed $orderid
     * @return
     */
    private function getOrder($modname, $orderid)
    {
        $sql = "SELECT * FROM " . NV_WALLET_TABLE . "_orders WHERE order_mod=:order_mod AND order_id=:order_id";
        $sth = $this->db->prepare($sql);
        $sth->bindParam(':order_mod', $modname, PDO::PARAM_STR);
        $sth->bindParam(':order_id', $orderid, PDO::PARAM_STR);
        $sth->execute();
        if ($sth->rowCount()) {
            $order = $sth->fetch();
        } else {
            $order = array();
        }
        return $order;
    }

    /**
     * nukeviet_wallet::getOrderPaid()
     *
     * @param mixed $modname
     * @param mixed $orderid
     * @param mixed $checksum
     * @return
     */
    public function getOrderPaid($modname, $orderid, $checksum)
    {
        $order = $this->getOrder($modname, $orderid);
        if (empty($order)) {
            return false;
        }
        if (strcasecmp($this->getOrderChecksum($order, $order['paid_status'], $order['paid_time']), $checksum) !== 0) {
            return false;
        }
        return array($order['paid_status'], $order['paid_time'], $order['paid_id']);
    }

    /**
     * nukeviet_wallet::getOrderChecksum()
     *
     * @param mixed $order
     * @param bool $paid_status
     * @param bool $paid_time
     * @return
     */
    private function getOrderChecksum($order, $paid_status = false, $paid_time = false)
    {
        $params = array(
            'id' => $order['id'],
            'order_mod' => $order['order_mod'],
            'order_message' => $order['order_message'],
            'money_amount' => $order['money_amount'],
            'money_unit' => $order['money_unit'],
            'url_back' => $order['url_back'],
            'url_admin' => $order['url_admin'],
            'add_time' => $order['add_time']
        );
        if ($paid_status !== false) {
            $params['paid_status'] = $paid_status;
        }
        if ($paid_time !== false) {
            $params['paid_time'] = $paid_time;
        }
        return hash_hmac('SHA1', implode('', $params), $order['secret_code']);
    }

    /**
     * nukeviet_wallet::getResponseChecksum()
     *
     * @param mixed $order
     * @param mixed $paid_status
     * @param mixed $paid_time
     * @return
     */
    public function getResponseChecksum($order, $paid_status, $paid_time)
    {
        if (is_null($paid_status) or is_null($paid_time)) {
            throw new Exception('Error paid status or paid time');
        }
        return $this->getOrderChecksum($order, $paid_status, $paid_time);
    }

    /**
     * nukeviet_wallet::verifyOrderChecksum()
     *
     * @param mixed $checksum
     * @param mixed $order
     * @return
     */
    public function verifyOrderChecksum($checksum, $order)
    {
        if (strcasecmp($this->getOrderChecksum($order), $checksum) === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * nukeviet_wallet::getOrderPayUrl()
     *
     * @param mixed $order
     * @return
     */
    public function getOrderPayUrl($order)
    {
        $checksum = $this->getOrderChecksum($order);
        return NV_WALLET_SURL_OP_HEADER . '=pay&wpay=' . $order['id'] . '&wchecksum=' . $checksum;
    }

    /**
     * nukeviet_wallet::getOrderUniqueSecretcode()
     *
     * @return
     */
    private function getOrderUniqueSecretcode()
    {
        while (true) {
            $secret_code = nv_strtoupper(nv_genpass(20));
            if (!$this->db->query("SELECT secret_code FROM " . NV_WALLET_TABLE . "_orders WHERE secret_code='" . $secret_code . "'")->fetchColumn()) {
                break;
            }
        }
        return $secret_code;
    }

    /**
     * nukeviet_wallet::isError()
     *
     * @return
     */
    public function isError()
    {
        return $this->is_error;
    }
}
