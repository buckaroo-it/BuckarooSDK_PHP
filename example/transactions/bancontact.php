<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('bancontactmrcash')->pay([
    //'saveToken' => true, //SaveToken is the parameter used to indicate if a token is to be created, that can be used for Wallet Initiated Payments in the future.
    //'encryptedCardData' => '', //If this is set PayEncryoted Action will be used. The value of this parameter is the result of the "encryptCardData"-function of our Client Side Encryption SDK.
    'amountDebit' => 10.10
]);


//Refund
$response = $buckaroo->method('bancontactmrcash')->refund([
    'invoice'   => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10
]);
