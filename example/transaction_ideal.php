<?php 
require_once (__DIR__ . "/init.php");

use \Buckaroo\SDK\Transaction;

$orderId = 's_' . date("Ymd H:i:s");

$response = Transaction::create(
    $client,
    [
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
    ]
);

var_dump($response);die();