<?php 
require_once (__DIR__ . '/../includes/init.php');

use Buckaroo\SDK\Transaction;

try {
    $response = Transaction::create(
        $client,
        [
            'serviceName' => 'ideal',
            'serviceAction' => 'Refund',
            'invoice' => $orderId,
            'currency' => $currencyCode,
            'originalTransactionKey' => $originalTransactionKey,
            'amountCredit' => $amountCredit,
            'returnURL' => $returnURL,
            'returnURLCancel' => $returnURLCancel,
            'pushURL' => $pushURL,
        ]
    );
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}
