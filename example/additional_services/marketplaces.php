<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$marketplace = $buckaroo->method('marketplaces')->manually()->split([
    'daysUntilTransfer' => 2,
    'marketplace' => [
        'amount' => 10,
        'description' => 'INV0001 Commission Marketplace',
    ],
    'sellers' => [
        [
            'accountId' => '789C60F316D24B088ACD471',
            'amount' => 50,
            'description' => 'INV001 Payout Make-Up Products BV',
        ],
        [
            'accountId' => '369C60F316D24B088ACD238',
            'amount' => 35,
            'description' => 'INV0001 Payout Beauty Products BV',
        ],
    ],
]);

$response = $buckaroo->method('ideal')->combine($marketplace)->pay([
    'invoice' => uniqid(),
    'amountDebit' => 95.00,
    'issuer' => 'ABNANL2A',
]);
