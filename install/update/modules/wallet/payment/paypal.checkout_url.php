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
use PaypalServerSdkLib\Models\CheckoutPaymentIntent;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\AmountBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\ItemRequestBuilder;
use PaypalServerSdkLib\Models\Builders\MoneyBuilder;
use PaypalServerSdkLib\Models\Builders\OrderApplicationContextBuilder;
use PaypalServerSdkLib\Exceptions\ApiException;
use PaypalServerSdkLib\Exceptions\ErrorException;

// Khởi tạo client PayPal (Orders API v2)
$client = PaypalServerSdkClientBuilder::init()
    ->clientCredentialsAuthCredentials(
        ClientCredentialsAuthCredentialsBuilder::init($payment_config['clientid'], $payment_config['secret'])
    )
    ->environment($payment_config['mode'] == 'live' ? Environment::PRODUCTION : Environment::SANDBOX)
    ->build();

// Số tiền phải là chuỗi định dạng chuẩn USD (2 chữ số thập phân)
$money_value = number_format((float) $post['money_net'], 2, '.', '');

// PayPal giới hạn description của purchase unit tối đa 127 ký tự
$pu_description = function_exists('nv_substr') ? nv_substr($post['transaction_info'], 0, 127) : substr($post['transaction_info'], 0, 127);

$amount = AmountWithBreakdownBuilder::init('USD', $money_value)
    ->breakdown(
        AmountBreakdownBuilder::init()
            ->itemTotal(MoneyBuilder::init('USD', $money_value)->build())
            ->build()
    )
    ->build();

$item = ItemRequestBuilder::init($post['transaction_code'], MoneyBuilder::init('USD', $money_value)->build(), '1')
    ->description($post['transaction_info'])
    ->sku('1')
    ->build();

$purchaseUnit = PurchaseUnitRequestBuilder::init($amount)
    ->items([$item])
    ->description($pu_description)
    ->invoiceId($post['transaction_code'])
    ->customId($post['transaction_code'])
    ->build();

// Cấu hình trải nghiệm thanh toán và URL chuyển hướng
$applicationContext = OrderApplicationContextBuilder::init()
    ->brandName(NV_SERVER_NAME)
    ->userAction('PAY_NOW')
    ->shippingPreference('NO_SHIPPING')
    ->returnUrl($post['ReturnURL'])
    ->cancelUrl($post['ReturnURL'] . '&ucancel=1')
    ->build();

$orderBody = OrderRequestBuilder::init(CheckoutPaymentIntent::CAPTURE, [$purchaseUnit])
    ->applicationContext($applicationContext)
    ->build();

try {
    $apiResponse = $client->getOrdersController()->createOrder([
        'body' => $orderBody,
        'prefer' => 'return=minimal'
    ]);

    if ($apiResponse->isSuccess()) {
        $order = $apiResponse->getResult();

        // Lấy link phê duyệt (approve/payer-action) để chuyển hướng khách hàng
        foreach ((array) $order->getLinks() as $link) {
            $rel = $link->getRel();
            if ($rel == 'approve' || $rel == 'payer-action') {
                $url = $link->getHref();
                break;
            }
        }

        if (empty($url)) {
            $error = 'PayPal: Error get approve link';
        }
    } else {
        $error = 'PayPal: Error create order';
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
