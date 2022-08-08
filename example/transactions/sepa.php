<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('sepadirectdebit')->pay([
    'customerAccountName' => 'TEST BANK USED BY IBAN SERVICE',
    'customerBic' => 'TESTNL2A',
    'customerIban' => 'NL13TEST0123456789',
    'amountDebit' => 10.10
]);

//Refund
$response = $buckaroo->method('sepadirectdebit')->refund([
    'invoice'   => 'sdk.serpentscode.com_INVOICE_NO_628d386d9b9d4', //Set invoice number of the transaction to refund
    'originalTransactionKey' => 'B6DDFC65AE074CB7AF2C0EA015D473D0', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10
]);
