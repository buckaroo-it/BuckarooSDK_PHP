<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$payload = [
    'amountDebit'       => 9.5,
    'order'             => uniqid(),
    'invoice'           => uniqid(),
    'description'       => 'This is a test order',
    'serviceParameters' => [
        'invoiceDate'       => '22-01-2018',
        'customerType'      => 'Company',
        'articles'      => [
            [
                'identifier'        => uniqid(),
                'description'       => 'Blue Toy Car',
                'quantity'          => '1',
                'price'             => 10.00
            ]
        ],
        'company'       => [
            'name'      => 'My Company B.V.',
            'chamberOfCommerce' => '123456'
        ],
        'customer'      => [
            'gender'        => '1',
            'initials'      => 'J.S.',
            'firstName' => 'Test',
            'lastName' => 'Aflever',
            'email' => 'billingcustomer@buckaroo.nl',
            'phone' => '0610000000',
            'birthDate' => '01-01-1990',
            'address'   => [
                'street' => 'Hoofdstraat',
                'housenumber'   => '2',
                'streetNumberAdditional' => 'a',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'country'=> 'NL'
            ]
        ],
        'subtotal'      => [
            [
                'name'      => 'Korting',
                'value'     => -2.00
            ],
            [
                'name'      => 'Betaaltoeslag',
                'value'     => 0.50
            ],
            [
                'name'      => 'Verzendkosten',
                'value'     => 1.00
            ]
        ]
    ]
];

//Also accepts json
//Pay
$response = $buckaroo->method('in3')->pay($payload);

//Pay installment
$response = $buckaroo->method('in3')->payInInstallments($payload);

//Refund
$response = $buckaroo->method('in3')->refund([
    'amountCredit' => 10,
    'invoice' => '10000480',
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
]);