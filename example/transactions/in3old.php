<?php
require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$payload = [
    'amountDebit' => 9.5,
    'order' => uniqid(),
    'invoice' => uniqid(),
    'description' => 'This is a test order',
    'invoiceDate' => '22-01-2018',
    'customerType' => 'Company',
    'email' => 'test@buckaroo.nl',
    'phone' => [
        'mobile' => '0612345678',
    ],
    'articles' => [
        [
            'identifier' => uniqid(),
            'description' => 'Blue Toy Car',
            'quantity' => '1',
            'price' => 10.00,
        ],
    ],
    'company' => [
        'companyName' => 'My Company B.V.',
        'chamberOfCommerce' => '123456',
    ],
    'customer' => [
        'gender' => Gender::FEMALE,
        'initials' => 'J.S.',
        'lastName' => 'Aflever',
        'email' => 'billingcustomer@buckaroo.nl',
        'phone' => '0610000000',
        'culture' => 'nl-NL',
        'birthDate' => '1990-01-01',
    ],
    'address' => [
        'street' => 'Hoofdstraat',
        'houseNumber' => '2',
        'houseNumberAdditional' => 'a',
        'zipcode' => '8441EE',
        'city' => 'Heerenveen',
        'country' => 'NL',
    ],
    'subtotals' => [
        [
            'name' => 'Korting',
            'value' => -2.00,
        ],
        [
            'name' => 'Betaaltoeslag',
            'value' => 0.50,
        ],
        [
            'name' => 'Verzendkosten',
            'value' => 1.00,
        ],
    ],
];

//Also accepts json
//Pay
$response = $buckaroo->method('in3Old')->pay($payload);

//Pay installment
$response = $buckaroo->method('in3Old')->payInInstallments($payload);

//Refund
$response = $buckaroo->method('in3Old')->refund([
    'amountCredit' => 10,
    'invoice' => '10000480',
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
]);
