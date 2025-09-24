<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('wero')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'description' => 'Payment for testinvoice123',
]);

//Authorize
$response = $buckaroo->method('wero')->authorize([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'description' => 'Payment for testinvoice123',
]);

//Cancel Authorize
$response = $buckaroo->method('wero')->cancelAuthorize([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
]);

//Capture
$response = $buckaroo->method('wero')->capture([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'description' => 'Payment for testinvoice123',
]);

//Refund
$response = $buckaroo->method('wero')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
]);