<?php
require_once (__DIR__ . '/../includes/init.php');

use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\PaymentMethodFactory;

$request = $app->prepareTransactionRequest();

$paymentMethod = PaymentMethodFactory::getPaymentMethod($client, PaymentMethod::IDEAL);
$paymentMethod->setBankCode($request, 'ABNANL2A');
$response = $paymentMethod->pay($request);

$app->handleResponse($response);
