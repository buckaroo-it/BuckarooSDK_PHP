<?php
require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('paybybank')->pay([
    'returnURL' => 'https://example.com/return',
    'amountDebit' => 10,
    'description' => 'Payment for testinvoice123',
    'issuer' => 'ABNANL2A',
]);

//Refund
$response = $buckaroo->method('paybybank')->refund([
    'invoice' => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10,
]);