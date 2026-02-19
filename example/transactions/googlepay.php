<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
// Note: paymentData should be the base64 encoded token from Google Pay API
$response = $buckaroo->method('googlepay')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'paymentData' => 'BASE64_ENCODED_GOOGLE_PAY_TOKEN',
    'customerCardName' => 'John Doe',
]);

//Pay
// Note: paymentData should be the base64 encoded token from Google Pay API
$response = $buckaroo->method('googlepay')->payRemainder([
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'paymentData' => 'BASE64_ENCODED_GOOGLE_PAY_TOKEN',
    'customerCardName' => 'John Doe',
]);

//Refund
$response = $buckaroo->method('googlepay')->refund([
    'amountCredit' => 10,
    'invoice' => '10000480',
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
]);
