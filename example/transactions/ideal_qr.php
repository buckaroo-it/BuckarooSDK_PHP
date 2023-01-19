<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Create payment
$response = $buckaroo->method('ideal_qr')->generate([
    'description' => 'Test purchase',
    'minAmount' => '0.10',
    'maxAmount' => '10.0',
    'imageSize' => '2000',
    'purchaseId' => 'Testpurchase123',
    'isOneOff' => false,
    'amount' => '1.00',
    'amountIsChangeable' => true,
    'expiration' => '2030-09-30',
    'isProcessing' => false,
]);
