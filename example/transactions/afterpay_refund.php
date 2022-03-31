<?php 
require_once (__DIR__ . '/../includes/init.php');
require_once (__DIR__ . '/../html/header.php');

use Buckaroo\Transaction;

try {
    $response = Transaction::create(
        $client,
        [
            'originalTransactionKey' => $originalTransactionKey,
            'method' => 'afterpay',
            'serviceAction' => 'Refund',
            'amountCredit' => 10.10,
            'invoice' => \Buckaroo\Example\App::getOrderId(),
            'currency' => $_ENV['BPE_EXAMPLE_CURRENCY_CODE'],
            'returnURL' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            'returnURLCancel' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            'pushURL' => $_ENV['BPE_EXAMPLE_RETURN_URL']
        ]
    );
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}

require_once (__DIR__ . '/../html/footer.php');

