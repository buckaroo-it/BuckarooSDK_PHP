<?php
require(__DIR__ . '/../vendor/autoload.php');

\Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->load();

$client = new \Buckaroo\Client($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$request = new \Buckaroo\Payload\TransactionRequest();
$request->setMethod('ideal');
$request->setAmountDebit(1.23);
$request->setInvoice('sdk' . time());
$request->setServiceParameter('issuer', 'ABNANL2A');

$response = $client->post($request);
if ($response && $response->hasRedirect()) {
    echo $response->getRedirectUrl();
}

