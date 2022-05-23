<?php 
require_once (__DIR__ . '/../includes/init.php');
require_once (__DIR__ . '/../html/header.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->refund([
    'method'                    => 'afterpay',
    'invoice'                   => '', //Set invoice number of the transaction to refund
    'originalTransactionKey'    => '', //Set transaction key of the transaction to refund
    'amountCredit'              => 10.10
]);


//$payload = ['method' => 'afterpay',
//            'originalTransactionKey' => '', //Set transaction key of the transaction to refund
//            'amountCredit' => 50.30
//            ];
//
//$payload = json_encode($payload); //Payload can be also json
//
//try {
//    $buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
//    $response = $buckaroo->refund($payload);
//    $app->handleResponse($response);
//} catch (\Exception $e) {
//    $app->handleException($e);
//}
//
//require_once (__DIR__ . '/../html/footer.php');