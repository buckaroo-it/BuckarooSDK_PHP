<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Verify
$response = $buckaroo->method('surepay')->verify([
    'bankAccount' => [
        'iban' => 'NL13TEST0123456789',
        'accountName' => 'John Doe',
    ],
]);
