<?php
$websiteKey = "PUT_YOUR_WEBSITE_KEY_HERE";
$secretKey = "PUT_YOUR_SECRET_KEY_HERE";
$baseUrl = 'https://PUT_YOUR_WEBSITE_URL_HERE/example/';

$debug = true;
$orderId = 'sdk_' . date('ymdHis') . rand(1, 99);
$currencyCode = 'EUR';
$returnURL = $baseUrl . 'return.php';
$returnURLCancel = $baseUrl . 'return.php';
$pushURL =  $baseUrl . 'push.php';
$ip = '45.14.110.5';

