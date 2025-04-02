<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->method('clicktopay')->pay([
    'currency' => 'EUR',
    'amountDebit' => 0.01,
    'invoice' => "ClickToPay_0001",
    'description' => "test ClickToPay",
    "clientIP" => [
        "type" => 0,
        "address" => "0.0.0.0"
    ],
    'continueOnIncomplete' => "1",
]);
