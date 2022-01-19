<?php 
require_once (__DIR__ . '/../includes/init.php');

use Buckaroo\Payload\TransactionRequest;

$request = new TransactionRequest();
$request->setServiceName('ideal');
$request->setServiceVersion(2);
$request->setServiceAction('Pay');
$request->setAmountDebit(10.10);
$request->setInvoice(\Buckaroo\Example\App::getOrderId());
$request->setOrder(\Buckaroo\Example\App::getOrderId());
$request->setCurrency($_ENV['BPE_EXAMPLE_CURRENCY_CODE']);
$request->setReturnURL($_ENV['BPE_EXAMPLE_RETURN_URL']);
$request->setReturnURLCancel($_ENV['BPE_EXAMPLE_RETURN_URL']);
$request->setPushURL($_ENV['BPE_EXAMPLE_RETURN_URL']);
$request->setServiceParameter('issuer', 'ABNANL2A');

try {
    $response = $client->post(
        $client->getTransactionUrl(),
        $request,
        'Buckaroo\Payload\TransactionResponse'
    );
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}
