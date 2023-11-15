<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$returnReponse = [
    "brq_amount" => "10.10",
    "brq_currency" => "EUR",
    "brq_customer_name" => "J. de Tèster",
    "brq_invoicenumber" => "SDKDevelopment.com_INVOICE_NO_628c6d032af90",
    "brq_ordernumber" => "SDKDevelopment.com_ORDER_NO_628c6d032af95",
    "brq_payer_hash" => "2d26d34584a4eafeeaa97eed10cfdae22ae64cdce1649a80a55fafca8850e3e22cb32eb7c8fc95ef0c6f966
    69a21651d4734cc568816f9bd59c2092911e6c0da",
    "brq_payment" => "D44ACDD0F99D4A1C811D2CD3EFDB05BA",
    "brq_payment_method" => "ideal",
    "brq_SERVICE_ideal_consumerBIC" => "RABONL2U",
    "brq_SERVICE_ideal_consumerIBAN" => "NL44RABO0123456789",
    "brq_SERVICE_ideal_consumerIssuer" => "ABN AMRO",
    "brq_SERVICE_ideal_consumerName" => "J. de Tèster",
    "brq_SERVICE_ideal_transactionId" => "0000000000000001",
    "brq_statuscode" => "190",
    "brq_statuscode_detail" => "S001",
    "brq_statusmessage" => "Transaction successfully processed",
    "brq_test" => "true",
    "brq_timestamp" => "2022-05-24 07:29:09",
    "brq_transactions" => "4C1BE53E2C42412AB32A799D9316E7DD",
    "brq_websitekey" => "IBjihN7Fhp",
    "brq_signature" => "bf7a62c830da2d2e004199919a8fe0d53b0668f5",
];

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$replyHandler = $buckaroo->payment($returnReponse['brq_payment_method'])->handleReply($returnReponse);
$replyHandler->validate();

$replyHandler->isValid();
