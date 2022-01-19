<?php 
require_once (__DIR__ . '/../includes/init.php');

use Buckaroo\Transaction;

try {
    $response = Transaction::create(
        $client,
        [
            'serviceName' => 'ideal',
            'serviceAction' => 'Refund',
            'invoice' => $orderId,
            'currency' => $currencyCode,
            'originalTransactionKey' => 'C2B9092A1C5943B5AB2C30070765C5F5', //origin brq_transactions from Pay transaction 
            'amountCredit' => 10.10,
            'returnURL' => $returnURL,
            'returnURLCancel' => $returnURLCancel,
            'pushURL' => $pushURL,
        ]
    );
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}
