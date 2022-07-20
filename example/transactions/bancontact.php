<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->payment('bancontactmrcash')->pay([
    //'saveToken' => true, //SaveToken is the parameter used to indicate if a token is to be created, that can be used for Wallet Initiated Payments in the future.
    //'encryptedCardData' => '', //If this is set PayEncryoted Action will be used. The value of this parameter is the result of the "encryptCardData"-function of our Client Side Encryption SDK.
    'amountDebit' => 10.10
]);


//Refund
$response = $buckaroo->payment('bancontactmrcash')->refund([
    'invoice'   => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10
]);
