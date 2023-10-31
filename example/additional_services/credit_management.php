<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

// Sometimes we need to combine multiple payments.
// By adding "manually," it will not execute immediately but rather return the built payload.
// With the returned payload, we can combine it with the next payment.

$invoice = $buckaroo->method('credit_management')->manually()->createCombinedInvoice([
                'invoice' => rand(1000, 9999),
                'applyStartRecurrent' => 'False',
                'invoiceAmount' => 10.00,
                'invoiceAmountVAT' => 1.00,
                'invoiceDate' => date('Y-m-d'),
                'dueDate' => date('Y-m-d'),
                'schemeKey' => '2amq34',
                'maxStepIndex' => 1,
                'allowedServices' => 'ideal,mastercard',
                'debtor' => [
                    'code' => 'johnsmith4',
                ],
                'email' => 'youremail@example.nl',
                'phone' => [
                    'mobile' => '06198765432',
                ],
                'person' => [
                    'culture' => 'nl-NL',
                    'title' => 'Msc',
                    'initials' => 'JS',
                    'firstName' => 'Test',
                    'lastNamePrefix' => 'Jones',
                    'lastName' => 'Aflever',
                    'gender' => Gender::MALE,
                ],
                'company' => [
                    'culture' => 'nl-NL',
                    'name' => 'My Company Corporation',
                    'vatApplicable' => true,
                    'vatNumber' => 'NL140619562B01',
                    'chamberOfCommerce' => '20091741',
                ],
                'address' => [
                    'street' => 'Hoofdtraat',
                    'houseNumber' => '90',
                    'houseNumberSuffix' => 'A',
                    'zipcode' => '8441ER',
                    'city' => 'Heerenveen',
                    'state' => 'Friesland',
                    'country' => 'NL',
                ],
            ]);

// In this case, we have the payload stored in the $invoice variable.
// We can now combine it with the next payment using the combine method.

$response = $buckaroo->method('sepadirectdebit')->combine($invoice)->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
    'iban' => 'NL13TEST0123456789',
    'bic' => 'TESTNL2A',
    'collectdate' => date('Y-m-d'),
    'mandateReference' => '1DCtestreference',
    'mandateDate' => '2022-07-03',
    'customer' => [
        'name' => 'John Smith',
    ],
]);
