<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->payment('buckaroo_wallet')->createWallet([
    'walletId'   => uniqid(),
    'email'         => 'test@buckaroo.nl',
    'customer'       => [
        'firstName'     => 'John',
        'lastName'      => 'Doe'
    ],
    'bankAccount'   => [
        'iban'      => 'NL13TEST0123456789',
    ]
]);