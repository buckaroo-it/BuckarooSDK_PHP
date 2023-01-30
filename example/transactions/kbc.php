<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('kbcpaymentbutton')->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
]);


//Refund
$response = $buckaroo->method('kbcpaymentbutton')->refund([
    'invoice' => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10,
]);
