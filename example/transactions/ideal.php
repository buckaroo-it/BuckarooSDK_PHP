<?php 
require_once (__DIR__ . '/../includes/init.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->pay([
    'method' => 'ideal',
    'issuer' => 'ABNANL2A',
    'amountDebit' => 10.10
]);


//$payload = ['method' => 'ideal',
//            'issuer' => 'ABNANL2A',
//            'amountDebit' => 10.10
//           ];
//
//$payload = json_encode($payload); //Payload can be also json
//
//try {
//    $buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
//    $response = $buckaroo->pay($payload);
//    $app->handleResponse($response);
//} catch (\Exception $e) {
//    $app->handleException($e);
//}