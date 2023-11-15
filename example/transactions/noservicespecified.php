<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
//continueOnIncomplete is mandatory for a no service specified payment.
//With the settings below the merchant can chose Bancontact or PayPal on the checkout page.
$response = $buckaroo->method(null)->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
    'servicesSelectableByClient' => 'ideal,bancontactmrcash,paypal',
    'servicesExcludedForClient' => 'ideal',
    'continueOnIncomplete' => '1',
]);


//Refund
$response = $buckaroo->method(null)->refund([
    'invoice' => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10,
]);
