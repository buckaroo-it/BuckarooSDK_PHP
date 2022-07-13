<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$invoice = $buckaroo->payment('credit_management')->manually()->createCombinedInvoice([
                'invoice'               => str_random(),
                'applyStartRecurrent'   => 'False',
                'invoiceAmount'         => 10.00,
                'invoiceAmountVAT'      => 1.00,
                'invoiceDate'           => carbon()->format('Y-m-d'),
                'dueDate'           => carbon()->addDay(30)->format('Y-m-d'),
                'schemeKey'         => '2amq34',
                'maxStepIndex'      => 1,
                'allowedServices'   => 'ideal,mastercard',
                'debtor'        => [
                    'code'  => 'johnsmith4'
                ],
                'email'     => 'youremail@example.nl',
                'phone'     => [
                    'mobile'     => '06198765432'
                ],
                'person'      => [
                    'culture'   => 'nl-NL',
                    'title'     => 'Msc',
                    'initials'  => 'JS',
                    'firstName' => 'Test',
                    'lastNamePrefix' => 'Jones',
                    'lastName' => 'Aflever',
                    'gender'   => Gender::MALE
                ],
                'company'       => [
                    'culture'       => 'nl-NL',
                    'name'          => 'My Company Corporation',
                    'vatApplicable' => true,
                    'vatNumber'     => 'NL140619562B01',
                    'chamberOfCommerce' => '20091741'
                ],
                'address'   => [
                    'street'            => 'Hoofdtraat',
                    'houseNumber'       => '90',
                    'houseNumberSuffix' => 'A',
                    'zipcode'           => '8441ER',
                    'city'              => 'Heerenveen',
                    'state'             => 'Friesland',
                    'country'           => 'NL'
                ]
            ]);

$response = $buckaroo->payment('sepadirectdebit')->combine($invoice)->pay([
    'invoice'           => uniqid(),
    'amountDebit'       => 10.10,
    'iban'              => 'NL13TEST0123456789',
    'bic'               => 'TESTNL2A',
    'collectdate'       => carbon()->addDays(60)->format('Y-m-d'),
    'mandateReference'  => '1DCtestreference',
    'mandateDate'       => '2022-07-03',
    'customer'          => [
        'name'          => 'John Smith'
    ]
]);