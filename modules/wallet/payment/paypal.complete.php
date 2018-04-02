<?php

/**
 * @Project WALLET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Friday, March 9, 2018 6:24:54 AM
 */

if (!defined('NV_IS_MOD_WALLET'))
    die('Stop!!!');

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;

// Hủy bỏ giao dịch
if ($nv_Request->get_int('ucancel', 'get', 0)) {
    $responseData['transaction_status'] = -1;
} else {
    // Kiểm tra giao dịch trả về
    $OAuthTokenCredential = new \PayPal\Auth\OAuthTokenCredential($payment_config['clientid'], $payment_config['secret']);
    $apiContext = new \PayPal\Rest\ApiContext($OAuthTokenCredential);
    $apiContext->setConfig(array(
        'mode' => $payment_config['mode'] // live or sandbox
    ));

    try {
        $paymentId = $nv_Request->get_title('paymentId', 'get', '');
        $payment = Payment::get($paymentId, $apiContext);

        $PayerID = $nv_Request->get_title('PayerID', 'get', '');
        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);

        try {
            $result = $payment->execute($execution, $apiContext);

            try {
                $payment = Payment::get($paymentId, $apiContext);

                $paymentId = $payment->getId();
                /**
                 * ["created", "approved", "failed", "partially_completed", "in_progress"]
                 */
                $paymentState = strtolower($payment->getState());
                $paymentCreateTime = $payment->getCreateTime();
                $paymentUpdateTime = $payment->getUpdateTime();

                $transactions = $payment->getTransactions();
                $payer = $payment->getPayer();
                $payerPayerInfo = $payer->getPayerInfo();

                $transactionAmount = $transactions[0]->getAmount()->getTotal();
                $transactionInvoiceNumber = $transactions[0]->getInvoiceNumber();

                $related_resources = $transactions[0]->getRelatedResources();
                $related_sale = $related_resources[0]->getSale();

                $transactionID = $related_sale->getId();
                $transactionPaymentMode = $related_sale->getPaymentMode();

                /**
                 * ["completed", "partially_refunded", "pending", "refunded", "denied"]
                 */
                $transactionState = strtolower($related_sale->getState());

                /**
                 * CHARGEBACK
                 * GUARANTEE
                 * BUYER_COMPLAINT
                 * REFUND
                 * UNCONFIRMED_SHIPPING_ADDRESS
                 * ECHECK
                 * INTERNATIONAL_WITHDRAWAL
                 * RECEIVING_PREFERENCE_MANDATES_MANUAL_ACTION
                 * PAYMENT_REVIEW
                 * REGULATORY_REVIEW
                 * UNILATERAL
                 * VERIFICATION_REQUIRED
                 * TRANSACTION_APPROVED_AWAITING_FUNDING
                 */
                $transactionReasonCode = $related_sale->getReasonCode();
                $transactionFee = $related_sale->getTransactionFee();
                $transactionFeeCurrency = $transactionFee->getCurrency();
                $transactionFeeValue = $transactionFee->getValue(); // String has dot, comma
                $transactionCreateTime = $related_sale->getCreateTime();
                $transactionUpdateTime = $related_sale->getUpdateTime();

                $payerStatus = $payer->getStatus();
                $payerEmail = $payerPayerInfo->getEmail();
                $payerFirstName = $payerPayerInfo->getFirstName();
                $payerLastName = $payerPayerInfo->getLastName();
                $payerPhone = $payerPayerInfo->getPhone();
                $payerCountryCode = $payerPayerInfo->getCountryCode();

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
                    'paymentId' => $paymentId,
                    'paymentCreateTime' => $paymentCreateTime,
                    'paymentUpdateTime' => $paymentUpdateTime,
                    'transactionPaymentMode' => $transactionPaymentMode,
                    'transactionFee' => $transactionFeeValue . ' ' . $transactionFeeCurrency,
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
                if ($paymentState == 'approved') {
                    if ($transactionState == 'completed') {
                        // Thành công
                        $responseData['transaction_status'] = 4;
                    } elseif ($transactionState == 'pending') {
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
            } catch (PayPalConnectionException $ex) {
                $errorData = nv_object2array(json_decode($ex->getData()));
                $error = $errorData['message'];
            }
        } catch (PayPalConnectionException $ex) {
            $errorData = nv_object2array(json_decode($ex->getData()));
            $error = $errorData['message'];
        }

    } catch (PayPalConnectionException $ex) {
        $errorData = nv_object2array(json_decode($ex->getData()));
        $error = $errorData['message'];
    }
}
