<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('transfer')->pay([
    'amountDebit' => 10.10,
    'serviceParameters' => [
        'customer' => [
            'gender' => Gender::MALE, // 0 = unkinown / 1 = male / 2 = female
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => 'your@email.com',
            'country' => 'NL',
        ],
        'dateDue' => date("Y-m-d"),
        'sendMail' => true,
    ]
]);

//Refund
$response = $buckaroo->method('transfer')->refund([
    'invoice'   => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10
]);
