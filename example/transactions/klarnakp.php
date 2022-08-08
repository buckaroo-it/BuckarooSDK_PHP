<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Create payment
$response = $buckaroo->method('klarnakp')->pay([
    'amountDebit'       => 50.30,
    'order'             => uniqid(),
    'invoice'           => uniqid(),
    'serviceParameters' => [
        'articles'      => [
            [
                'articleNumber' => uniqid(),
                'reservationNumber' => '2377577452',
                'articleQuantity' => '2'
            ],
            [
                'articleNumber' => uniqid(),
                'reservationNumber' => '2377577353',
                'articleQuantity' => '2'
            ],
        ]
    ]
]);

//Refund
$response = $buckaroo->method('klarnakp')->refund([
    'amountCredit' => 10,
    'invoice' => '10000480',
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
]);
