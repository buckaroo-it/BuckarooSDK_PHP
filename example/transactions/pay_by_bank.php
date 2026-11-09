<?php
require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Retrieve the list of banks the customer can choose from.
//Each item has an 'id' (pass it as 'issuer') and a 'name' (show it to the customer).
$issuers = $buckaroo->method('paybybank')->issuers();

//Also accepts json
//Pay
$response = $buckaroo->method('paybybank')->pay([
    'returnURL' => 'https://example.com/return',
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'description' => 'Payment for testinvoice123',
    'issuer' => 'ABNANL2A',
    'countryCode' => 'NL', //Must match the issuer's country: 'NL' or 'BE'
]);

//Pay with a Belgian bank: pass a Belgian issuer together with countryCode 'BE'
$response = $buckaroo->method('paybybank')->pay([
    'returnURL' => 'https://example.com/return',
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'description' => 'Payment for testinvoice123',
    'issuer' => 'GEBABEBB', //BNP Paribas Fortis
    'countryCode' => 'BE',
]);

//Refund
$response = $buckaroo->method('paybybank')->refund([
    'invoice' => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10,
]);
