<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('billink')->pay([
    'amountDebit' => 50.30,
    'order' => uniqid(),
    'invoice' => uniqid(),
    'trackAndTrace' => 'TR0F123456789',
    'vATNumber' => '2',
    'billing' => [
        'recipient' => [
            'category' => 'B2B',
            'careOf' => 'John Smith',
            'title' => 'Female',
            'initials' => 'JD',
            'firstName' => 'John',
            'lastName' => 'Do',
            'birthDate' => '01-01-1990',
            'chamberOfCommerce' => 'TEST',
        ],
        'address' => [
            'street' => 'Hoofdstraat',
            'houseNumber' => '13',
            'houseNumberAdditional' => 'a',
            'zipcode' => '1234AB',
            'city' => 'Heerenveen',
            'country' => 'NL',
        ],
        'phone' => [
            'mobile' => '0698765433',
            'landline' => '0109876543',
        ],
        'email' => 'test@buckaroo.nl',
    ],
    'shipping' => [
        'recipient' => [
            'category' => 'B2C',
            'careOf' => 'John Smith',
            'title' => 'Male',
            'initials' => 'JD',
            'firstName' => 'John',
            'lastName' => 'Do',
            'birthDate' => '1990-01-01',
        ],
        'address' => [
            'street' => 'Kalverstraat',
            'houseNumber' => '13',
            'houseNumberAdditional' => 'b',
            'zipcode' => '4321EB',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ],
    ],
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'price' => '20.10',
            'priceExcl' => 5,
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'vatPercentage' => '21',
            'quantity' => '1',
            'price' => 10.10,
            'priceExcl' => 5,
        ],
    ],
]);

//Authorize
$response = $buckaroo->method('billink')->authorize([
    'amountDebit' => 50.30,
    'order' => uniqid(),
    'invoice' => uniqid(),
    'trackAndTrace' => 'TR0F123456789',
    'vATNumber' => '2',
    'billing' => [
        'recipient' => [
            'category' => 'B2B',
            'careOf' => 'John Smith',
            'title' => 'Female',
            'initials' => 'JD',
            'firstName' => 'John',
            'lastName' => 'Do',
            'birthDate' => '01-01-1990',
            'chamberOfCommerce' => 'TEST',
        ],
        'address' => [
            'street' => 'Hoofdstraat',
            'houseNumber' => '13',
            'houseNumberAdditional' => 'a',
            'zipcode' => '1234AB',
            'city' => 'Heerenveen',
            'country' => 'NL',
        ],
        'phone' => [
            'mobile' => '0698765433',
            'landline' => '0109876543',
        ],
        'email' => 'test@buckaroo.nl',
    ],
    'shipping' => [
        'recipient' => [
            'category' => 'B2C',
            'careOf' => 'John Smith',
            'title' => 'Male',
            'initials' => 'JD',
            'firstName' => 'John',
            'lastName' => 'Do',
            'birthDate' => '1990-01-01',
        ],
        'address' => [
            'street' => 'Kalverstraat',
            'houseNumber' => '13',
            'houseNumberAdditional' => 'b',
            'zipcode' => '4321EB',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ],
    ],
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'price' => '20.10',
            'priceExcl' => 5,
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'vatPercentage' => '21',
            'quantity' => '1',
            'price' => 10.10,
            'priceExcl' => 5,
        ],
    ],
]);

//Capture
$response = $buckaroo->method('billink')->capture([
    'originalTransactionKey' => '74AD098CCFAA4F739FE16279B5059B6B', //Set transaction key of the transaction to capture
    'invoice' => '62905fa2650f4', //Set invoice id
    'amountDebit' => 50.30, //set amount to capture
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'price' => '20.10',
            'priceExcl' => 5,
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'vatPercentage' => '21',
            'quantity' => '1',
            'price' => 10.10,
            'priceExcl' => 5,
        ],
    ],
]);

//Cancel authorize
$response = $buckaroo->method('billink')->cancelAuthorize([
    'originalTransactionKey' => '74AD098CCFAA4F739FE16279B5059B6B', //Set transaction key of the authorized transaction to cancel
    'invoice' => '62905fa2650f4', //Set invoice id
    'AmountCredit' => 10, //set amount to capture
]);

//Refund
$response = $buckaroo->method('billink')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
]);
