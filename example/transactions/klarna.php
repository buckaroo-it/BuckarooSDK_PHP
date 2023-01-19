<?php
require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->method('klarna')->pay([
    'amountDebit' => 50.30,
    'order' => uniqid(),
    'invoice' => uniqid(),
    'billing' => [
        'recipient' => [
            'category' => 'B2C',
            'gender' => 'female',
            'firstName' => 'John',
            'lastName' => 'Do',
            'birthDate' => '1990-01-01',
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
            'category' => 'B2B',
            'gender' => 'male',
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
        'email' => 'test@buckaroo.nl',
    ],
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'price' => '20.10',
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'vatPercentage' => '21',
            'quantity' => '1',
            'price' => '10.10',
        ],
    ],
]);

//PayInInstallments
$response = $buckaroo->method('klarna')->payInInstallments([
    'amountDebit' => 50.30,
    'order' => uniqid(),
    'invoice' => uniqid(),
    'currency' => 'GBP',
    'billing' => [
        'recipient' => [
            'category' => 'B2C',
            'gender' => 'female',
            'firstName' => 'John',
            'lastName' => 'Do',
            'birthDate' => '1990-01-01',
        ],
        'address' => [
            'street' => 'Hoofdstraat',
            'houseNumber' => '13',
            'houseNumberAdditional' => 'a',
            'zipcode' => '1234AB',
            'city' => 'Heerenveen',
            'country' => 'GB',
        ],
        'phone' => [
            'mobile' => '0698765433',
            'landline' => '0109876543',
        ],
        'email' => 'test@buckaroo.nl',
    ],
    'shipping' => [
        'recipient' => [
            'category' => 'B2B',
            'gender' => 'male',
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
            'country' => 'GB',
        ],
        'email' => 'test@buckaroo.nl',
    ],
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'price' => '20.10',
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'vatPercentage' => '21',
            'quantity' => '1',
            'price' => '10.10',
        ],
    ],
]);

//Refund
$response = $buckaroo->method('klarna')->refund([
    'invoice' => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10,
]);
