<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('billink')->pay([
    'amountDebit'       => 50.30,
    'order'             => uniqid(),
    'invoice'           => uniqid(),
    'serviceParameters' => [
        'articles'      => [
            [
                'identifier' => 'Articlenumber1',
                'description' => 'Blue Toy Car',
                'vatPercentage' => '21',
                'quantity' => '2',
                'grossUnitPrice' => '20.10'
            ],
            [
                'identifier' => 'Articlenumber2',
                'description' => 'Red Toy Car',
                'vatPercentage' => '21',
                'quantity' => '1',
                'grossUnitPrice' => '10.10'
            ],
        ],
        'customer'      => [
            'useBillingInfoForShipping' => false,
            'billing'                   => [
                'firstName' => 'Test',
                'lastName' => 'Acceptatie',
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => '0109876543',
                'street' => 'Hoofdstraat',
                'streetNumber' => '80',
                'streetNumberAdditional' => 'A',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'country' => 'NL',
                'salutation' => 'Mr',
                'birthDate' => '01-01-1990'
            ],
            'shipping'                  => [
                'firstName' => 'Test',
                'lastName' => 'Aflever',
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => '0109876543',
                'street' => 'Hoofdstraat',
                'streetNumber' => '80',
                'streetNumberAdditional' => 'A',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'country' => 'NL',
                'salutation' => 'Mr',
                'birthDate' => '01-01-1990'
            ]
        ]
    ]
]);

//Refund
$response = $buckaroo->method('billink')->refund([
    'amountCredit' => 10,
    'invoice'       => 'testinvoice 123',
    'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX'
]);