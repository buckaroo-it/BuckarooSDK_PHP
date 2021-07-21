<?php 
require_once (__DIR__ . '/init.php');

use Buckaroo\SDK\Transaction;
use Buckaroo\SDK\Example\App;

try {
    $response = Transaction::create(
        $client,
        [
            'serviceName' => 'ideal',
            'serviceVersion' => 2,
            'serviceAction' => 'Pay',
            'amountDebit' => 10.10,
            'invoice' => $orderId,
            'order' => $orderId,
            'currency' => $currencyCode,
            'issuer' => 'ABNANL2A',
            'returnURL' => $returnURL,
            'returnURLCancel' => $returnURLCancel,
            'pushURL' => $pushURL,
        ]
    );
    App::handleResponse($response);
} catch (\Exception $e) {
    App::handleException($e);
}
