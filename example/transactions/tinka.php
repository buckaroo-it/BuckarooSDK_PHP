<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('tinka')->pay([
    'amountDebit'       => 3.5,
    'order'             => uniqid(),
    'invoice'           => uniqid(),
    'description'       => 'This is a test order',
    'serviceParameters' => [
        'paymentMethod'       => 'Credit',
        'deliveryMethod'      => 'Locker',
        'deliveryDate'          => '09-12-2022',
        'articles'      => [
            [
                'type'              => 1,
                'description'       => 'Blue Toy Car',
                'brand'             => 'Ford Focus',
                'manufacturer'      => 'Ford',
                'color'             => 'Red',
                'size'              => 'Small',
                'quantity'          => '1',
                'grossUnitPrice'    => '3.5',
                'unitCode'         => 'test'
            ]
        ],
        'customer'      => [
            'gender'        => '1',
            'initials'      => 'J.S.',
            'firstName' => 'Test',
            'lastName' => 'Aflever',
            'birthDate' => '01-01-1990',
            'billing'                   => [
                'prefixLastName'    => 'the',
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => '0109876543',
                'street' => 'Hoofdstraat',
                'streetNumber' => '80',
                'streetNumberAdditional' => 'A',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'country' => 'NL',
            ],
            'shipping'                  => [
                'externalName' => 'Test',
                'phone' => '0109876543',
                'street' => 'Hoofdstraat',
                'streetNumber' => '80',
                'streetNumberAdditional' => 'A',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'country' => 'NL',
            ]
        ]
    ]
]);

//Refund
$response = $buckaroo->method('tinka')->refund([
    'amountCredit' => 10,
    'invoice'       => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
]);