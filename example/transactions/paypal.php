<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('paypal')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
]);

//Recurrent Payment
$response = $buckaroo->method('paypal')->payRecurrent([
    'amountDebit' => 10,
    'originalTransactionKey' => 'C32C0B52E1FE4A37835FFB1716XXXXXX',
    'invoice' => uniqid(),
]);

//Pay with extra info
$response = $buckaroo->method('paypal')->extraInfo([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'customer' => [
        'name' => 'John Smith',
    ],
    'address' => [
        'street' => 'Hoofstraat 90',
        'street2' => 'Street 2',
        'city' => 'Heerenveen',
        'state' => 'Friesland',
        'zipcode' => '8441AB',
        'country' => 'NL',
    ],
    'phone' => [
        'mobile' => '0612345678',
    ],
]);

//Refund
$response = $buckaroo->method('paypal')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
]);
