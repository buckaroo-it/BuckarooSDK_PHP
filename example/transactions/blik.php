<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('blik')->pay([
    'currency'      => 'PLN',
    'amountDebit'   => 10.00,
    'invoice'       => 'Blik Test Plugins Example',
    'description'   => 'Blik Test Plugins Example',
    'email'         => 'test@buckar00.nl'
]);