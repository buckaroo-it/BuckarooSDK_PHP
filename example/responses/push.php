<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;
use Buckaroo\Handlers\Reply\ReplyHandler;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//START HTTP POST PUSH
$post_data = [
    "brq_amount" => "10.10",
    "brq_currency" => "EUR",
    "brq_customer_name" => "J. de Tèster",
    "brq_invoicenumber" => "SDKDevelopment.com_INVOICE_NO_628c6d032af90",
    "brq_ordernumber" => "SDKDevelopment.com_ORDER_NO_628c6d032af95",
    "brq_payer_hash" => "2d26d34584a4eafeeaa97eed10cfdae22ae64cdce1649a80a55fafca8850e3e22cb32eb7c8fc95ef0c6f96669a21651d4734cc568816f9bd59c2092911e6c0da",
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

$reply_handler = new ReplyHandler($buckaroo->client()->config(), $post_data);
$reply_handler->validate();
$reply_handler->isValid(); // Return either true or false
//END HTTP POST PUSH


//START JSON PUSH
$auth_header = 'IBjihN7Fhp:0YvyjYAzDQ28W+hQi80f2nhe0Z1QFJLbz7IH//6LsAU=:cad1832100784f57a6e6de835d9f3638:1658227572';
$post_data = '{"Transaction":{"Key":"5340604668D74435AA344E1428ED1292","Invoice":"62d68b6c8ab0c","ServiceCode":"ideal","Status":{"Code":{"Code":190,"Description":"Success"},"SubCode":{"Code":"S001","Description":"Transaction successfully processed"},"DateTime":"2022-07-19T12:46:12"},"IsTest":true,"Order":"ORDER_NO_62d68b6ca2df3","Currency":"EUR","AmountDebit":10.1,"TransactionType":"C021","Services":[{"Name":"ideal","Action":null,"Parameters":[{"Name":"consumerIssuer","Value":"ABN AMRO"},{"Name":"transactionId","Value":"0000000000000001"},{"Name":"consumerName","Value":"J. de Tèster"},{"Name":"consumerIBAN","Value":"NL44RABO0123456789"},{"Name":"consumerBIC","Value":"RABONL2U"}],"VersionAsProperty":2}],"CustomParameters":null,"AdditionalParameters":{"List":[{"Name":"initiated_by_magento","Value":"1"},{"Name":"service_action","Value":"something"}]},"MutationType":1,"RelatedTransactions":null,"IsCancelable":false,"IssuingCountry":null,"StartRecurrent":false,"Recurring":false,"CustomerName":"J. de Tèster","PayerHash":"2d26d34584a4eafeeaa97eed10cfdae22ae64cdce1649a80a55fafca8850e3e22cb32eb7c8fc95ef0c6f96669a21651d4734cc568816f9bd59c2092911e6c0da","PaymentKey":"AEC974D455FF4A4B9B4C21E437A04838","Description":null}}';
$uri = 'https://buckaroo.nextto.dev/push';

$reply_handler = new ReplyHandler($buckaroo->client()->config(), $post_data, $auth_header, $uri);
$reply_handler->validate();
$reply_handler->isValid(); // Return either true or false
//END JSON PUSH
