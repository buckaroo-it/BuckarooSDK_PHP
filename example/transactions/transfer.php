<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->payment('transfer')->pay([
    'customerGender' => 0, // 0 = unkinown / 1 = male / 2 = female
    'customerFirstName' => 'John',
    'customerLastName' => 'Smith',
    'customerEmail' => 'your@email.com',
    'customerCountry' => 'NL',
    'dueData' => date(),
    'sendMail' => true,
    'amountDebit' => 10.10
]);

//Refund
$response = $buckaroo->payment('transfer')->refund([
    'invoice'   => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10
]);