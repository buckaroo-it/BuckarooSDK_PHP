<?php 
require_once (__DIR__ . '/../includes/init.php');

use Buckaroo\Transaction;

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
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}
