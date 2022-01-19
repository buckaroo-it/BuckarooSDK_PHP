<?php 
require_once (__DIR__ . '/../includes/init.php');

use Buckaroo\Transaction;

try {
    $response = Transaction::create(
        $client,
        [
            'serviceName' => 'ideal',
            'serviceAction' => 'Refund',
            'invoice' => \Buckaroo\Example\App::getOrderId(),
            'currency' => $_ENV['BPE_EXAMPLE_CURRENCY_CODE'],
            'originalTransactionKey' => 'C2B9092A1C5943B5AB2C30070765C5F5', //origin brq_transactions from Pay transaction 
            'amountCredit' => 10.10,
            'returnURL' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            'returnURLCancel' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            'pushURL' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
        ]
    );
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}
