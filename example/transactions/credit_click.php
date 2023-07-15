<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('creditclick')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'email' => 'billingcustomer@buckaroo.nl',
    'customer' => [
        'firstName' => 'Test',
        'lastName' => 'Aflever',
    ],
]);

//Refund
$response = $buckaroo->method('creditclick')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'description' => 'refund',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
    'serviceParameters' => [
        'reason' => 'RequestedByCustomer',
    ],
]);
