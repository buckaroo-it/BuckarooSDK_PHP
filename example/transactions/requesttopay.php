<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method("requesttopay")->pay([
    'amountDebit' => 3.5,
    'invoice' => uniqid(),
    'customer' => [
        'name' => 'J. De Tester',
    ],
]);

$response = $buckaroo->method('requesttopay')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
]);
