<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

// PaymentOrder
$response = $buckaroo->method('banking')->paymentOrder([
    'amountCredit' => 0.1,
    'invoice' => uniqid(),
    'description' => 'Banking PaymentOrder Test',
    'accountHolderName' => 'John Smith',
    'iban' => 'NL44RABO0123456789',
    'processingDate' => '12/12/2026',
    'bic' => 'PAYMINBBXXX',
    'purpose' => 'Testing',
    'structuredIssuerType' => 'ISO',
    'structuredReference' => 'RF18539007547034',
]);

// InstantPaymentOrder
$instantResponse = $buckaroo->method('banking')->instantPaymentOrder([
    'amountCredit' => 0.1,
    'invoice' => uniqid(),
    'description' => 'Banking Instant PaymentOrder Test',
    'accountHolderName' => 'John Smith',
    'iban' => 'NL44RABO0123456789',
]);
