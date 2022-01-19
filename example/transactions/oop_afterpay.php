<?php 
require_once (__DIR__ . '/../includes/init.php');
require_once (__DIR__ . '/../html/header.php');

use Buckaroo\Payload\TransactionRequest;

$request = new TransactionRequest();
$request->setServiceName('afterpay');
$request->setServiceVersion(1);
$request->setServiceAction('Pay');
$request->setAmountDebit(10.10);
$request->setInvoice(\Buckaroo\Example\App::getOrderId());
$request->setOrder(\Buckaroo\Example\App::getOrderId());
$request->setCurrency($_ENV['BPE_EXAMPLE_CURRENCY_CODE']);
$request->setReturnURL($_ENV['BPE_EXAMPLE_RETURN_URL']);
$request->setReturnURLCancel($_ENV['BPE_EXAMPLE_RETURN_URL']);
$request->setPushURL($_ENV['BPE_EXAMPLE_RETURN_URL']);

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
$request->setServiceParameter('Phone', '0109876543', 'BillingCustomer');

$request->setServiceParameter('Salutation', 'Mr', 'BillingCustomer');
$request->setServiceParameter('BirthDate', '01-01-1990', 'BillingCustomer');

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

$request->setClientIP($_ENV['BPE_EXAMPLE_IP']);

try {
    $response = $client->post(
        $client->getTransactionUrl(),
        $request,
        'Buckaroo\Payload\TransactionResponse'
    );
    $app->handleResponse($response);
} catch (\Exception $e) {
    $app->handleException($e);
}

require_once (__DIR__ . '/../html/footer.php');
