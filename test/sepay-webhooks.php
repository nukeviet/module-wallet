<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$url = 'http://wallet.nukeviet4.my/sepay-webhooks.php';

$data = [
    'gateway' => 'Vietcombank',
    'transactionDate' => '2023-11-11 17:33:35',
    'accountNumber' => '0041000123456',
    'subAccount' => '',
    'code' => '',
    'content' => 'GD0000000005',
    'transferType' => 'in',
    'description' => 'SD TK 0041000123456 +10,000VND luc 11-11-2023 17:33:35. SD 19.234.567.890VND. Ref 737016.062023.043327.GD0000000005',
    'transferAmount' => 10000,
    'referenceCode' => '737016.062023.043327',
    'accumulated' => 19234567890,
    'id' => 12345678
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'Origin: https://sepay.vn',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/119.0',
    'Authorization: Apikey EYWtQwv35PtomMxJ5Mb2j57a7xfAeXkv',
]);

$response = curl_exec($ch);
curl_close($ch);

echo '<pre><code>';
echo htmlspecialchars(print_r($response, true));
die('</code></pre>');
