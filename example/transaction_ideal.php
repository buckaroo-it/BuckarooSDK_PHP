<?php 
require_once (__DIR__ . "/init.php");

$orderId = 's_' . date("Ymd H:i:s");

$response = \Buckaroo\SDK\Transaction::create([
    "mode" => 'test',
    "websiteKey" => $websiteKey,
    "secretKey" => $secretKey,
    "serviceName" => 'ideal',
    "serviceVersion" => 2,
    "amountDebit" => '10.10',
    "invoice" => $orderId,
    "order" => $orderId,
    "currency" => $currencyCode,
    "issuer" => 'ABNANL2A',
    "returnURL" => $returnURL,
    "returnURLCancel" => $returnURLCancel,
    "pushURL" => $pushURL,
]);

var_dump($response);die();