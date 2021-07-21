<?php 
require_once (__DIR__ . '/init.php');

use \Buckaroo\SDK\Transaction;

$response = Transaction::create(
    $client,
    [
        'serviceName' => 'afterpay',
        'serviceVersion' => 1,
        'serviceAction' => 'Pay',
        'amountDebit' => 10.10,
        'invoice' => $orderId,
        'order' => $orderId,
        'currency' => $currencyCode,
        'returnURL' => $returnURL,
        'returnURLCancel' => $returnURLCancel,
        'pushURL' => $pushURL,
        'clientIP' => $ip,
        'serviceParameters' => [
            [ 'name' => 'Description', 'value' => 'Blue Toy Car', 'groupType' => 'Article', 'groupId' => 1 ],
            [ 'name' => 'GrossUnitPrice', 'value' => 10.10, 'groupType' => 'Article', 'groupId' => 1 ],
            [ 'name' => 'VatPercentage', 'value' => 21, 'groupType' => 'Article', 'groupId' => 1 ],
            [ 'name' => 'Quantity', 'value' => 1, 'groupType' => 'Article', 'groupId' => 1 ],
            [ 'name' => 'Identifier', 'value' => 'Articlenumber12345', 'groupType' => 'Article', 'groupId' => 1 ],

            [ 'name' => 'Category', 'value' => 'Person', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'FirstName', 'value' => 'Test', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'LastName', 'value' => 'Acceptatie', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'Street', 'value' => 'Hoofdstraat', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'StreetNumber', 'value' => '90', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'StreetNumberAdditional', 'value' => 'A', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'PostalCode', 'value' => '8441EE', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'City', 'value' => 'Heerenveen', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'PostalCode', 'value' => '8441EE', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'Country', 'value' => 'NL', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'Email', 'value' => 'billingcustomer@buckaroo.nl', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'Phone', 'value' => '0109876543', 'groupType' => 'BillingCustomer' ],

            [ 'name' => 'Salutation', 'value' => 'Mr', 'groupType' => 'BillingCustomer' ],
            [ 'name' => 'BirthDate', 'value' => '01-01-1990', 'groupType' => 'BillingCustomer' ],

            [ 'name' => 'FirstName', 'value' => 'Test', 'groupType' => 'ShippingCustomer' ],
            [ 'name' => 'LastName', 'value' => 'Aflever', 'groupType' => 'ShippingCustomer' ],
            [ 'name' => 'Street', 'value' => 'Afleverstraat', 'groupType' => 'ShippingCustomer' ],
            [ 'name' => 'StreetNumber', 'value' => '80', 'groupType' => 'ShippingCustomer' ],
            [ 'name' => 'StreetNumberAdditional', 'value' => 'B', 'groupType' => 'ShippingCustomer' ],
            [ 'name' => 'PostalCode', 'value' => '7881ER', 'groupType' => 'ShippingCustomer' ],
            [ 'name' => 'City', 'value' => 'Leeuwarden', 'groupType' => 'ShippingCustomer' ],
            [ 'name' => 'Country', 'value' => 'NL', 'groupType' => 'ShippingCustomer' ],
            [ 'name' => 'Email', 'value' => 'shippingcustomer@buckaroo.nl', 'groupType' => 'ShippingCustomer' ],
        ]
    ]
);

var_dump($response);die();
