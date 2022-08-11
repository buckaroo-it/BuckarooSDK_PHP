<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $this->buckaroo->method('paypal')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid()
]);

//Refund
$response = $this->buckaroo->method('paypal')->refund([
    'amountCredit' => 10,
    'invoice'       => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
]);