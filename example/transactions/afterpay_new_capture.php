<?php 
require_once (__DIR__ . '/../includes/init.php');
require_once (__DIR__ . '/../html/header.php');

use Buckaroo\Buckaroo;

$payload = ['method' => 'afterpay',
            'originalTransactionKey' => '', //Set transaction key of the transaction to capture
            'invoice' => 'sdk_xxxxxxx', //Set invoice id
            'amountDebit' => 50.30 //set amount to capture
            ];
            
$payload = json_encode($payload); //Payload can be also json

try {
    $buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
    $response = $buckaroo->capture($payload);
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}

require_once (__DIR__ . '/../html/footer.php');