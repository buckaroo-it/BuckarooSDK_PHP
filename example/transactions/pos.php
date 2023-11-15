<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('pospayment')->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
    'terminalID' => '50000001',
]);
