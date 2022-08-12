<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->method('afterpaydigiaccept')->pay([
    'amountDebit'       => 40,
    'order'             => uniqid(),
    'invoice'           => uniqid(),
    'serviceParameters' => [
        'b2b'                       => false,
        'articles'      => [
            [
                'articleId' => uniqid(),
                'articleDescription' => 'Blue Toy Car',
                'articleUnitprice' => '10.00',
                'articleQuantity' => '2',
                'articleVatcategory' => '1'
            ],
            [
                'articleId' => uniqid(),
                'articleDescription' => 'Blue Toy Car',
                'articleUnitprice' => '10.00',
                'articleQuantity' => '2',
                'articleVatcategory' => '1'
            ],
        ],
        'customer'      => [
            'useBillingInfoForShipping' => false,
            'billing'                   => [
                'firstName' => 'Test',
                'lastName' => 'Acceptatie',
                'email' => 'billingcustomer@buckaroo.nl',
                'street' => 'Hoofdstraat',
                'housenumber'   => '2',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'phone' => '0610000000',
                'birthDate' => '01-01-1990'
            ],
            'shipping'                  => [
                'firstName' => 'Test',
                'lastName' => 'Aflever',
                'email' => 'billingcustomer@buckaroo.nl',
                'street' => 'Hoofdstraat',
                'housenumber'   => '2',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'phone' => '0610000000',
                'birthDate' => '01-01-1990'
            ]
        ]
    ]
]);

//Refund
$response = $buckaroo->method('afterpaydigiaccept')->refund([
    'amountCredit' => 10,
    'invoice' => '10000480',
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
]);