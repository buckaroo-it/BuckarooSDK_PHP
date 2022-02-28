<?php

require_once(__DIR__ . '/../includes/init.php');

use Buckaroo\Model\Customer;
use Buckaroo\Model\Article;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\PaymentMethodFactory;

$request = $app->prepareTransactionRequest();

$request->setClientIP($_ENV['BPE_EXAMPLE_IP']);

$paymentMethod = PaymentMethodFactory::getPaymentMethod($client, PaymentMethod::AFTERPAY);

$article1 = new Article();
$article1->setName('Blue Toy Car');
$article1->setPrice(10.10);
$article1->setVat(21);
$article1->setQuantity(1);
$article1->setId('sku12345');

$paymentMethod->setArticleItem($request, $article1);

$billingCustomer = new Customer();
$billingCustomer->setFirstname('Test');
$billingCustomer->setLastname('Acceptatie');
$billingCustomer->setStreet('Hoofdstraat');
$billingCustomer->setHouseNumber('90');
$billingCustomer->setHouseNumberAddition('A');
$billingCustomer->setPostalCode('8441EE');
$billingCustomer->setCity('Heerenveen');
$billingCustomer->setCountryCode('NL');
$billingCustomer->setEmail('billingcustomer@buckaroo.nl');
$billingCustomer->setPhoneNumber('0109876543');
$billingCustomer->setCustomerId('12345');
$billingCustomer->setIdentificationId('123');
$billingCustomer->setCategory('Person');
$billingCustomer->setSalutation('Mr');
$billingCustomer->setBirthday('01-01-1990');

$paymentMethod->setBillingCustomer($request, $billingCustomer);

$shippingCustomer = new Customer();
$shippingCustomer->setFirstname('Test');
$shippingCustomer->setLastname('Aflever');
$shippingCustomer->setStreet('Afleverstraat');
$shippingCustomer->setHouseNumber('80');
$shippingCustomer->setHouseNumberAddition('B');
$shippingCustomer->setPostalCode('7881ER');
$shippingCustomer->setCity('Leeuwarden');
$shippingCustomer->setCountryCode('NL');
$shippingCustomer->setEmail('shippingcustomer@buckaroo.nl');
$shippingCustomer->setPhoneNumber('0109876543');
$shippingCustomer->setCustomerId('12345');
$shippingCustomer->setIdentificationId('0109876543');
$shippingCustomer->setCategory('Person');
$shippingCustomer->setSalutation('Mr');
$shippingCustomer->setBirthday('01-01-1990');

$paymentMethod->setShippingCustomer($request, $shippingCustomer);

$response = $paymentMethod->pay($request);

$app->handleResponse($response);
