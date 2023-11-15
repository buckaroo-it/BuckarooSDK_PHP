<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->method('buckaroo_wallet')->createWallet([
    'walletId' => uniqid(),
    'email' => 'test@buckaroo.nl',
    'customer' => [
        'firstName' => 'John',
        'lastName' => 'Doe',
    ],
    'bankAccount' => [
        'iban' => 'NL13TEST0123456789',
    ],
]);
