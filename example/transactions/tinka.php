<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('tinka')->pay([
    'amountDebit' => 3.5,
    'order' => uniqid(),
    'invoice' => uniqid(),
    'description' => 'This is a test order',
    'paymentMethod' => 'Credit',
    'deliveryMethod' => 'Locker',
    'deliveryDate' => '2030-01-01',
    'articles' => [
        [
            'type' => 1,
            'description' => 'Blue Toy Car',
            'brand' => 'Ford Focus',
            'manufacturer' => 'Ford',
            'color' => 'Red',
            'size' => 'Small',
            'quantity' => '1',
            'price' => '3.5',
            'unitCode' => 'test',
        ],
    ],
    'customer' => [
        'gender' => Gender::MALE,
        'firstName' => 'Buck',
        'lastName' => 'Aroo',
        'initials' => 'BA',
        'birthDate' => '1990-01-01',
    ],
    'billing' => [
        'recipient' => [
            'lastNamePrefix' => 'the',
        ],
        'email' => 'billingcustomer@buckaroo.nl',
        'phone' => [
            'mobile' => '0109876543',
        ],
        'address' => [
            'street' => 'Hoofdstraat',
            'houseNumber' => '80',
            'houseNumberAdditional' => 'A',
            'zipcode' => '8441EE',
            'city' => 'Heerenveen',
            'country' => 'NL',
        ],
    ],
    'shipping' => [
        'recipient' => [
            'lastNamePrefix' => 'the',
        ],
        'email' => 'billingcustomer@buckaroo.nl',
        'phone' => [
            'mobile' => '0109876543',
        ],
        'address' => [
            'street' => 'Hoofdstraat',
            'houseNumber' => '80',
            'houseNumberAdditional' => 'A',
            'zipcode' => '8441EE',
            'city' => 'Heerenveen',
            'country' => 'NL',
        ],
    ],
]);

//Refund
$response = $buckaroo->method('tinka')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
]);
