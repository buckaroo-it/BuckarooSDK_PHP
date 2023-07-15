<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;
use Buckaroo\Resources\Constants\RecipientCategory;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->method('afterpay')->pay([
    'amountDebit' => 50.30,
    'order' => uniqid(),
    'invoice' => uniqid(),
    'clientIP' => '127.0.0.1',
    'billing' => [
        'recipient' => [
            'category' => RecipientCategory::PERSON,
            'careOf' => 'John Smith',
            'title' => 'Mrs',
            'firstName' => 'John',
            'lastName' => 'Do',
            'birthDate' => '1990-01-01',
            'conversationLanguage' => 'NL',
            'identificationNumber' => 'IdNumber12345',
            'customerNumber' => 'customerNumber12345',
        ],
        'address' => [
            'street' => 'Hoofdstraat',
            'houseNumber' => '13',
            'houseNumberAdditional' => 'a',
            'zipcode' => '1234AB',
            'city' => 'Heerenveen',
            'country' => 'NL',
        ],
        'phone' => [
            'mobile' => '0698765433',
            'landline' => '0109876543',
        ],
        'email' => 'test@buckaroo.nl',
    ],
    'shipping' => [
        'recipient' => [
            'category' => RecipientCategory::COMPANY,
            'careOf' => 'John Smith',
            'companyName' => 'Buckaroo B.V.',
            'firstName' => 'John',
            'lastName' => 'Do',
            'chamberOfCommerce' => '12345678',
        ],
        'address' => [
            'street' => 'Kalverstraat',
            'houseNumber' => '13',
            'houseNumberAdditional' => 'b',
            'zipcode' => '4321EB',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ],
    ],
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'price' => '20.10',
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'vatPercentage' => '21',
            'quantity' => '1',
            'price' => '10.10',
        ],
    ],
]);

//Authorize
$response = $buckaroo->method('afterpay')->authorize([
    'amountDebit' => 50.30,
    'order' => uniqid(),
    'invoice' => uniqid(),
    'clientIP' => '127.0.0.1',
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'grossUnitPrice' => '20.10',
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'vatPercentage' => '21',
            'quantity' => '1',
            'grossUnitPrice' => '10.10',
        ],
    ],
    'customer' => [
        'useBillingInfoForShipping' => false,
        'billing' => [
            'firstName' => 'Test',
            'lastName' => 'Acceptatie',
            'email' => 'billingcustomer@buckaroo.nl',
            'phone' => '0109876543',
            'street' => 'Hoofdstraat',
            'streetNumber' => '80',
            'streetNumberAdditional' => 'A',
            'postalCode' => '8441EE',
            'city' => 'Heerenveen',
            'country' => 'NL',
            'salutation' => 'Mr',
            'birthDate' => '01-01-1990',
        ],
        'shipping' => [
            'firstName' => 'Test',
            'lastName' => 'Aflever',
            'email' => 'billingcustomer@buckaroo.nl',
            'phone' => '0109876543',
            'street' => 'Hoofdstraat',
            'streetNumber' => '80',
            'streetNumberAdditional' => 'A',
            'postalCode' => '8441EE',
            'city' => 'Heerenveen',
            'country' => 'NL',
            'salutation' => 'Mr',
            'birthDate' => '01-01-1990',
        ],
    ],
]);

//Capture
$response = $buckaroo->method('afterpay')->capture([
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX', //Set transaction key of the transaction to capture
    'invoice' => '628603a20c375', //Set invoice id
    'amountDebit' => 50.30, //set amount to capture
    'clientIP' => '127.0.0.1',
    'articles' => [
        [
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'grossUnitPrice' => '20.10',
        ],
        [
            'identifier' => 'Articlenumber2',
            'description' => 'Red Toy Car',
            'vatPercentage' => '21',
            'quantity' => '1',
            'grossUnitPrice' => '10.10',
        ],
    ],
]);

//Refund
$response = $buckaroo->method('afterpay')->refund([
    'invoice' => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10,
]);

//Partial refund
$response = $buckaroo->method('afterpay')->refund([
    'invoice' => 'testinvoice 123', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX', //Set transaction key of the transaction to refund
    'amountCredit' => 1.23,
    'articles' => [
        [
            'refundType' => 'Return',
            'identifier' => 'Articlenumber1',
            'description' => 'Blue Toy Car',
            'vatPercentage' => '21',
            'quantity' => '2',
            'price' => '20.10',
        ],
    ],
]);
