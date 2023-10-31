<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('applepay')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'paymentData' => uniqid(),
    'customerCardName' => 'Buck Aroo',
]);

//Pay Redirect
$response = $buckaroo->method('applepay')->payRedirect([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'servicesSelectableByClient' => 'applepay',
    'continueOnIncomplete' => '1',
]);

//Refund
$response = $buckaroo->method('applepay')->refund([
    'amountCredit' => 10,
    'invoice' => '10000480',
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
]);
