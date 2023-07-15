<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('giftcard')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'name' => 'boekenbon',
    'intersolveCardnumber' => '0000000000000000001',
    'intersolvePIN' => '1000',
]);

//Refund
$response = $buckaroo->method('giftcard')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
    'name' => 'boekenbon',
]);
