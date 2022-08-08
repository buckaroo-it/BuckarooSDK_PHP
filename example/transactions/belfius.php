<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('belfius')->pay([
    'amountDebit' => 10.10
]);

//Refund
$response = $buckaroo->method('belfius')->refund([
    'invoice'   => 'sdk.serpentscode.com_INVOICE_NO_628cf7f4f3c79', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '59AA593AFB3D4B4C8524ACB71EDDCE40', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10
]);
