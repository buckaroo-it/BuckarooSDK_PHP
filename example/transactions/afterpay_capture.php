<?php 
//require_once (__DIR__ . '/../includes/init.php');
//require_once (__DIR__ . '/../html/header.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->payment('afterpay')->capture([
    'originalTransactionKey' => '63C3576B74FE4D95A8B46EFC198B6E5E', //Set transaction key of the transaction to capture
    'invoice' => '628603a20c375', //Set invoice id
    'amountDebit' => 50.30, //set amount to capture
    'serviceParameters' => [
        'articles'      => [
            [
                'identifier' => 'Articlenumber1',
                'description' => 'Blue Toy Car',
                'vatPercentage' => '21',
                'quantity' => '2',
                'grossUnitPrice' => '20.10'
            ],
            [
                'identifier' => 'Articlenumber2',
                'description' => 'Red Toy Car',
                'vatPercentage' => '21',
                'quantity' => '1',
                'grossUnitPrice' => '10.10'
            ],
        ],
    ]
]);


//
//$payload = ['method' => 'afterpay',
//            'originalTransactionKey' => '', //Set transaction key of the transaction to capture
//            'invoice' => 'sdk_xxxxxxx', //Set invoice id
//            'amountDebit' => 50.30 //set amount to capture
//            ];
//
//$payload = json_encode($payload); //Payload can be also json
//
//try {
//    $buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
//    $response = $buckaroo->capture($payload);
//    $app->handleResponse($response);
//} catch (\Exception $e) {
//    $app->handleException($e);
//}
//
//require_once (__DIR__ . '/../html/footer.php');