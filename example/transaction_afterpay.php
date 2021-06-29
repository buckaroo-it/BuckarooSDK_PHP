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
        'serviceParameters' => [
            [ 'name' => 'Description', 'value' => 'Blue Toy Car', 'groupType' => 'Article', 'groupId' => 1 ],
            [ 'name' => 'GrossUnitPrice', 'value' => 10.10, 'groupType' => 'Article', 'groupId' => 1 ],
            [ 'name' => 'VatPercentage', 'value' => 21, 'groupType' => 'Article', 'groupId' => 1 ],
            [ 'name' => 'Quantity', 'value' => 1, 'groupType' => 'Article', 'groupId' => 1 ],
            [ 'name' => 'Identifier', 'value' => 'Articlenumber12345', 'groupType' => 'Article', 'groupId' => 1 ],

            [ 'name' => 'Identifier', 'value' => 'Articlenumber12345', 'groupType' => 'Article', 'groupId' => 1 ],
            //FINISH THIS!!!!!!!!!
        ]
    ]
);

$request = new TransactionRequest();
$request->setServiceName('afterpay');
$request->setServiceVersion(1);
$request->setServiceAction('Pay');
$request->setAmountDebit(10.10);
$request->setInvoice($orderId);
$request->setOrder($orderId);
$request->setCurrency($currencyCode);
$request->setReturnURL($returnURL);
$request->setReturnURLCancel($returnURLCancel);
$request->setPushURL($pushURL);

$request->setServiceParameter('Description', 'Blue Toy Car', 'Article', 1);
$request->setServiceParameter('GrossUnitPrice', 10.10, 'Article', 1);
$request->setServiceParameter('VatPercentage', 21, 'Article', 1);
$request->setServiceParameter('Quantity', 1, 'Article', 1);
$request->setServiceParameter('Identifier', 'Articlenumber12345', 'Article', 1);

$request->setServiceParameter('Category', 'Person', 'BillingCustomer');
$request->setServiceParameter('FirstName', 'Test', 'BillingCustomer');
$request->setServiceParameter('LastName', 'Acceptatie', 'BillingCustomer');
$request->setServiceParameter('Street', 'Hoofdstraat', 'BillingCustomer');
$request->setServiceParameter('StreetNumber', '90', 'BillingCustomer');
$request->setServiceParameter('StreetNumberAdditional', 'A', 'BillingCustomer');
$request->setServiceParameter('PostalCode', '8441EE', 'BillingCustomer');
$request->setServiceParameter('City', 'Heerenveen', 'BillingCustomer');
$request->setServiceParameter('Country', 'NL', 'BillingCustomer');
$request->setServiceParameter('Email', 'billingcustomer@buckaroo.nl', 'BillingCustomer');

$request->setServiceParameter('Salutation', 'Mr', 'BillingCustomer');
$request->setServiceParameter('BirthDate', '01-01-1990', 'BillingCustomer');
$request->setServiceParameter('Phone', '0109876543', 'BillingCustomer');

$request->setServiceParameter('FirstName', 'Test', 'ShippingCustomer');
$request->setServiceParameter('LastName', 'Aflever', 'ShippingCustomer');
$request->setServiceParameter('Street', 'Afleverstraat', 'ShippingCustomer');
$request->setServiceParameter('StreetNumber', '80', 'ShippingCustomer');
$request->setServiceParameter('StreetNumberAdditional', 'B', 'ShippingCustomer');
$request->setServiceParameter('PostalCode', '7881ER', 'ShippingCustomer');
$request->setServiceParameter('City', 'Leeuwarden', 'ShippingCustomer');
$request->setServiceParameter('Country', 'NL', 'ShippingCustomer');
$request->setServiceParameter('Email', 'shippingcustomer@buckaroo.nl', 'ShippingCustomer');

$request->setServiceParameter('Phone', '0109876543', 'ShippingCustomer');

$request->setClientIP('45.14.110.5');

try {
    $response = $client->post(
        $client->getTransactionUrl(),
        $request,
        'Buckaroo\SDK\Buckaroo\Payload\TransactionResponse'
    );
} catch (Exception $e) {
    return ['error' => $e->getMessage()];
}

var_dump($response);die();