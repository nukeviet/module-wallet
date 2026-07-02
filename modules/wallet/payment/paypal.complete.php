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

use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Models\OrderStatus;
use PaypalServerSdkLib\Models\CaptureStatus;
use PaypalServerSdkLib\Exceptions\ApiException;
use PaypalServerSdkLib\Exceptions\ErrorException;

// Hủy bỏ giao dịch
if ($nv_Request->get_int('ucancel', 'get', 0)) {
    $responseData['transaction_status'] = -1;
} else {
    // Khởi tạo client PayPal (Orders API v2)
    $client = PaypalServerSdkClientBuilder::init()
        ->clientCredentialsAuthCredentials(
            ClientCredentialsAuthCredentialsBuilder::init($payment_config['clientid'], $payment_config['secret'])
        )
        ->environment($payment_config['mode'] == 'live' ? Environment::PRODUCTION : Environment::SANDBOX)
        ->build();

    // Orders v2 trả về ID đơn hàng qua tham số token
    $orderId = $nv_Request->get_title('token', 'get', '');
    if (empty($orderId)) {
        // Tương thích ngược nếu URL cũ còn truyền paymentId
        $orderId = $nv_Request->get_title('paymentId', 'get', '');
    }

    try {
        // Capture (thu tiền) đơn hàng đã được khách phê duyệt
        $apiResponse = $client->getOrdersController()->captureOrder([
            'id' => $orderId,
            'prefer' => 'return=representation'
        ]);

        $order = $apiResponse->getResult();

        /**
         * ["CREATED", "SAVED", "APPROVED", "VOIDED", "COMPLETED", "PAYER_ACTION_REQUIRED"]
         */
        $orderStatus = strtoupper((string) $order->getStatus());

        $purchaseUnits = $order->getPurchaseUnits();
        $purchaseUnit = $purchaseUnits[0];

        // Mã giao dịch trên hệ thống (invoice_id đã đặt = transaction_code)
        $transactionInvoiceNumber = $purchaseUnit->getInvoiceId();

        // Thông tin lần thu tiền (capture)
        $capture = null;
        $payments = $purchaseUnit->getPayments();
        if ($payments !== null) {
            $captures = $payments->getCaptures();
            if (!empty($captures)) {
                $capture = $captures[0];
            }
        }

        if ($capture === null) {
            $error = 'PayPal: Capture info is null';
        } else {
            /**
             * ["COMPLETED", "DECLINED", "PARTIALLY_REFUNDED", "PENDING", "REFUNDED", "FAILED"]
             */
            $captureStatus = strtoupper((string) $capture->getStatus());

            $transactionID = $capture->getId();
            $transactionCreateTime = $capture->getCreateTime();
            $transactionUpdateTime = $capture->getUpdateTime();

            // Phí giao dịch
            $transactionFeeValue = '';
            $transactionFeeCurrency = '';
            $breakdown = $capture->getSellerReceivableBreakdown();
            if ($breakdown !== null && $breakdown->getPaypalFee() !== null) {
                $transactionFeeValue = $breakdown->getPaypalFee()->getValue(); // String has dot, comma
                $transactionFeeCurrency = $breakdown->getPaypalFee()->getCurrencyCode();
            }

            // Thông tin người thanh toán
            $payerStatus = $payerEmail = $payerFirstName = $payerLastName = $payerPhone = $payerCountryCode = '';
            $payer = $order->getPayer();
            $paypalSource = $order->getPaymentSource() !== null ? $order->getPaymentSource()->getPaypal() : null;

            if ($payer !== null) {
                $payerEmail = $payer->getEmailAddress();
                if ($payer->getName() !== null) {
                    $payerFirstName = $payer->getName()->getGivenName();
                    $payerLastName = $payer->getName()->getSurname();
                }
                if ($payer->getPhone() !== null && $payer->getPhone()->getPhoneNumber() !== null) {
                    $payerPhone = $payer->getPhone()->getPhoneNumber()->getNationalNumber();
                }
                if ($payer->getAddress() !== null) {
                    $payerCountryCode = $payer->getAddress()->getCountryCode();
                }
            } elseif ($paypalSource !== null) {
                $payerEmail = $paypalSource->getEmailAddress();
                if ($paypalSource->getName() !== null) {
                    $payerFirstName = $paypalSource->getName()->getGivenName();
                    $payerLastName = $paypalSource->getName()->getSurname();
                }
                if ($paypalSource->getAddress() !== null) {
                    $payerCountryCode = $paypalSource->getAddress()->getCountryCode();
                }
            }
            if ($paypalSource !== null) {
                $payerStatus = $paypalSource->getAccountStatus();
            }

            /**
             * Wallet bắt đầu xử lý
             */
            // Loại giao dịch
            $responseData['ordertype'] = (preg_match('/^GD/', $transactionInvoiceNumber) ? 'recharge' : 'pay');

            // ID giao dịch nếu nạp tiền hoặc là ID đơn hàng nếu thanh toán cho các module khác
            $responseData['orderid'] = intval(str_replace('GD', '', str_replace('WP', '', $transactionInvoiceNumber)));

            // Mã giao dịch trên cổng thanh toán
            $responseData['transaction_id'] = $transactionID;

            // Thời gian giao dịch trên hệ thống
            $responseData['transaction_time'] = strtotime($transactionCreateTime);

            // Lưu lại một số thông tin giao dịch khác
            $transaction_data = array(
                'paymentId' => $order->getId(),
                'paymentCreateTime' => $order->getCreateTime(),
                'paymentUpdateTime' => $order->getUpdateTime(),
                'transactionFee' => trim($transactionFeeValue . ' ' . $transactionFeeCurrency),
                'transactionUpdateTime' => $transactionUpdateTime,
                'payerStatus' => $payerStatus,
                'payerEmail' => $payerEmail,
                'payerFirstName' => $payerFirstName,
                'payerLastName' => $payerLastName,
                'payerPhone' => $payerPhone,
                'payerCountryCode' => $payerCountryCode
            );
            $responseData['transaction_data'] = serialize($transaction_data);

            // Trạng thái giao dịch chuẩn WALLET
            if ($orderStatus == OrderStatus::COMPLETED) {
                if ($captureStatus == CaptureStatus::COMPLETED) {
                    // Thành công
                    $responseData['transaction_status'] = 4;
                } elseif ($captureStatus == CaptureStatus::PENDING) {
                    // Bị tạm giữ
                    $responseData['transaction_status'] = 2;
                } else {
                    // Thất bại
                    $responseData['transaction_status'] = 3;
                }
            } else {
                // Thất bại
                $responseData['transaction_status'] = 3;
            }
        }
    } catch (ApiException $ex) {
        $error = $ex->getMessage();
        if ($ex instanceof ErrorException) {
            $message = $ex->getMessageProperty();
            if (!empty($message)) {
                $error = $message;
            }
        }
    }
}
