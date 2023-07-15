<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method("przelewy24")->pay([
    'amountDebit' => 3.5,
    'invoice' => uniqid(),
    'email' => 'test@test.nl',
    'customer' => [
        'firstName' => 'John',
        'lastName' => 'Smith',
    ],
]);

//Refund
$response = $buckaroo->method('przelewy24')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
]);
