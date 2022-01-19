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
            'invoice' => \Buckaroo\Example\App::getOrderId(),
            'order' => \Buckaroo\Example\App::getOrderId(),
            'currency' => $_ENV['BPE_EXAMPLE_CURRENCY_CODE'],
            'issuer' => 'ABNANL2A',
            'returnURL' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            'returnURLCancel' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            'pushURL' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
        ]
    );
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}
