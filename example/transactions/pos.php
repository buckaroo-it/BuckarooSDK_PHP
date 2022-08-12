<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('pospayment')->pay([
    'amountDebit' => 10.10,
    'serviceParameters' => [
        'terminalId' => '50000001',
    ]
]);