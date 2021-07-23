<?php 
require_once (__DIR__ . '/../includes/init.php');
require_once (__DIR__ . '/../html/header.php');

use Buckaroo\SDK\Transaction;

try {
    $response = Transaction::create(
        $client,
        [
            'originalTransactionKey' => $originalTransactionKey,
            'serviceName' => 'afterpay',
            'serviceAction' => 'Refund',
            'amountCredit' => 10.10,
            'invoice' => $orderId,
            'currency' => $currencyCode,
            'returnURL' => $returnURL,
            'returnURLCancel' => $returnURLCancel,
            'pushURL' => $pushURL
        ]
    );
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}

require_once (__DIR__ . '/../html/footer.php');

