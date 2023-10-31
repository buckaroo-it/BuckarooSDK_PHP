<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;
use Buckaroo\Resources\Constants\RecipientCategory;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->method('thunes')->pay([
    'amountDebit' => 30.20,
    'name' => 'monizzeecovoucher',
    'order' => uniqid(),
    'invoice' => uniqid(),
    'clientIP' => '127.0.0.1',
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'price' => '20.10',
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'price' => '10.10',
        ],
    ],
]);
