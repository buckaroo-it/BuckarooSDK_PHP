<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('wechatpay')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'serviceParameters' => [
        'locale' => 'en-US'
    ]
]);

//Refund
$response = $buckaroo->method('wechatpay')->refund([
    'amountCredit' => 10,
    'invoice'       => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
]);