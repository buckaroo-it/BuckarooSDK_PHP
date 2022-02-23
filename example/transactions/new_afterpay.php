<?php

require_once(__DIR__ . '/../includes/init.php');

use Buckaroo\Models\Address;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\PaymentMethodFactory;

$request = $app->prepareTransactionRequest();

$request->setServiceParameter('Category', 'Person', 'BillingCustomer');

$request->setServiceParameter('Salutation', 'Mr', 'BillingCustomer');
$request->setServiceParameter('BirthDate', '01-01-1990', 'BillingCustomer');

$request->setClientIP($_ENV['BPE_EXAMPLE_IP']);

$paymentMethod = PaymentMethodFactory::getPaymentMethod($client, PaymentMethod::AFTERPAY);
$paymentMethod->setTransactionRequest($request);

$paymentMethod->setArticleItem('Blue Toy Car', 10.10, 21, 1, 'Articlenumber12345');

$billingAddress = new Address();
$billingAddress->setFirstname('Test');
$billingAddress->setLastname('Acceptatie');
$billingAddress->setStreet(trim('Hoofdstraat');
$billingAddress->setHouseNumber('90');
$billingAddress->setHouseNumberAddition('A');
$billingAddress->setPostalCode('8441EE');
$billingAddress->setCity('Heerenveen');
$billingAddress->setCountryCode('NL');
$billingAddress->setEmail('billingcustomer@buckaroo.nl');
$billingAddress->setPhoneNumber('0109876543');

$paymentMethod->setBillingAddress($billingAddress);

$shippingAddress = new Address();
$shippingAddress->setFirstname('Test');
$shippingAddress->setLastname('Aflever');
$shippingAddress->setStreet(trim('Afleverstraat');
$shippingAddress->setHouseNumber('80');
$shippingAddress->setHouseNumberAddition('B');
$shippingAddress->setPostalCode('7881ER');
$shippingAddress->setCity('Leeuwarden');
$shippingAddress->setCountryCode('NL');
$shippingAddress->setEmail('shippingcustomer@buckaroo.nl');
$shippingAddress->setPhoneNumber('0109876543');

$paymentMethod->setShippingAddress($shippingAddress);

$response = $paymentMethod->pay($request);

$app->handleResponse($response);
