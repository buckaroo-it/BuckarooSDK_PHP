<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('belfius')->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
]);

//Refund
$response = $buckaroo->method('belfius')->refund([
    'invoice' => 'INVOICE_NO_628cf7f4f3c79', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '59AA593AFB3D4B4C8524ACB71EDDCE40', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10,
]);
