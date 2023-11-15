<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('sepadirectdebit')->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
    'iban' => 'NL13TEST0123456789',
    'bic' => 'TESTNL2A',
    'collectdate' => '2022-12-01',
    'mandateReference' => '1DCtestreference',
    'mandateDate' => '2022-07-03',
    'customer' => [
        'name' => 'John Smith',
    ],
]);

//Refund
$response = $buckaroo->method('sepadirectdebit')->refund([
    'invoice' => 'INVOICE_NO_628d386d9b9d4', //Set invoice number of the transaction to refund
    'originalTransactionKey' => 'B6DDFC65AE074CB7AF2C0EA015D473D0', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10,
]);
