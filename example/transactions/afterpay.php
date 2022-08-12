<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->method('afterpay')->pay([
    'amountDebit'       => 50.30,
    'order'             => 'aftpay_order_test',
    'invoice'           => 'aftpay_invoice_test',
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

//Authorize
$response = $buckaroo->method('afterpay')->authorize([
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

//Capture
$response = $buckaroo->method('afterpay')->capture([
    'originalTransactionKey' => '63C3576B74FE4D95A8B46EFC198B6E5E', //Set transaction key of the transaction to capture
    'invoice' => '628603a20c375', //Set invoice id
    'amountDebit' => 50.30, //set amount to capture
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
    ]
]);

//Refund
$response = $buckaroo->method('afterpay')->refund([
    'invoice'                   => '', //Set invoice number of the transaction to refund
    'originalTransactionKey'    => '', //Set transaction key of the transaction to refund
    'amountCredit'              => 10.10
]);