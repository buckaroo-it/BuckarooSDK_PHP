<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('transfer')->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
    'email' => 'your@email.com',
    'country' => 'NL',
    'dateDue' => date("Y-m-d"),
    'sendMail' => true,
    'customer' => [
        'gender' => Gender::MALE,
        'firstName' => 'John',
        'lastName' => 'Smith',
    ],
]);

//Refund
$response = $buckaroo->method('transfer')->refund([
    'invoice' => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10,
]);
