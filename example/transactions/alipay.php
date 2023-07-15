<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->method('alipay')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'useMobileView' => true,
]);

//Refund
$response = $buckaroo->method('alipay')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
]);
