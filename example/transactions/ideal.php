<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->payment('idealprocessing')->pay([
    'amountDebit' => 10.10,
    'serviceParameters' => [
        'issuer' => 'ABNANL2A',
    ]
]);

//Refund
$response = $buckaroo->payment('ideal')->refund([
    'invoice'   => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10
]);