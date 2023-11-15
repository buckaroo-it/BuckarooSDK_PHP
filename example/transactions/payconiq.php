<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('payconiq')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'description' => 'Payment for testinvoice123',
]);

//Refund
$response = $buckaroo->method('payconiq')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
]);
