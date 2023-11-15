<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$payload = [
    'amountDebit'       => 52.30,
    'description'       => 'in3 pay',
    'order'             => uniqid(),
    'invoice'           => uniqid(),
    'clientIP'      => '127.0.0.1',
    'billing'       => [
        'recipient'        => [
            'category'      => 'B2C',
            'initials'      => 'J',
            'firstName'      => 'John',
            'lastName'      => 'Dona',
            'birthDate'     => '1990-01-01',
            'customerNumber'        => '12345',
            'phone'                 => '0612345678',
            'country'               => 'NL',
            'companyName' => 'My Company B.V.',
            'chamberOfCommerce' => '123456'
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
            'phone' => '0698765433',
        ],
        'email' => 'test@buckaroo.nl',
    ],
    'shipping' => [
        'recipient' => [
            'category' => 'B2C',
            'careOf' => 'John Smith',
            'firstName' => 'John',
            'lastName' => 'Do',
            'chamberOfCommerce' => '123456'
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
            'type' => 'Physical',
            'description' => 'Blue Toy Car',
            'category' => 'test product1',
            'vatPercentage' => '21',
            'quantity' => '2',
            'price' => '20.10',
        ],
        [
            'identifier' => 'Articlenumber2',
            'type' => 'Physical',
            'description' => 'Red Toy Car',
            'category' => 'test product2',
            'vatPercentage' => '21',
            'quantity' => '1',
            'price' => '10.10',
        ],
        [
            'identifier' => 'USPShippingID',
            'type' => 'Physical',
            'description' => 'UPS',
            'category' => 'test product3',
            'vatPercentage' => '21',
            'quantity' => '1',
            'price' => '2',
        ],
    ]
];

//Also accepts json
//Pay
$response = $buckaroo->method('in3')->pay($payload);

//Refund
$response = $buckaroo->method('in3')->refund([
    'amountCredit' => 10,
    'invoice' => '10000480',
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
]);
