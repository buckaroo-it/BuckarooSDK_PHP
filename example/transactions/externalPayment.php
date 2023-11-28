<?php

require_once __DIR__.'/../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->method('externalPayment')->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
    'channel' => 'BackOffice'
]);

$buckaroo->method('externalPayment')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => $response->getTransactionKey(),
]);