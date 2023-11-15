<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('trustly')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'country' => 'DE',
    'customer' => [
        'firstName' => 'Test',
        'lastName' => 'Aflever',
    ],
]);

//Refund
$response = $buckaroo->method('trustly')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
]);
