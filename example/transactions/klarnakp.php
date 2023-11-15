<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->method('klarnakp')->reserve([
    'invoice' => uniqid(),
    'gender' => "1",
    'operatingCountry' => 'NL',
    'pno' => '01011990',
    'billing' => [
        'recipient' => [
            'firstName' => 'John',
            'lastName' => 'Do',
        ],
        'address' => [
            'street' => 'Neherkade',
            'houseNumber' => '1',
            'zipcode' => '2521VA',
            'city' => 'Gravenhage',
            'country' => 'NL',
        ],
        'phone' => [
            'mobile' => '0612345678',
        ],
        'email' => 'youremail@example.nl',
    ],
    'shipping' => [
        'recipient' => [
            'firstName' => 'John',
            'lastName' => 'Do',
        ],
        'address' => [
            'street' => 'Rosenburglaan',
            'houseNumber' => '216',
            'zipcode' => '4385 JM',
            'city' => 'Vlissingen',
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

//Refund
$response = $buckaroo->method('klarnakp')->refund([
    'amountCredit' => 10,
    'invoice' => '10000480',
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
]);
