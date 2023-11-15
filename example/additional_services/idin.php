<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Identify
$response = $buckaroo->method('idin')->identify([
    'issuer' => 'BANKNL2Y',
]);

//Verify
$response = $buckaroo->method('idin')->verify([
    'issuer' => 'BANKNL2Y',
]);

//Login
$response = $buckaroo->method('idin')->login([
    'issuer' => 'BANKNL2Y',
]);
