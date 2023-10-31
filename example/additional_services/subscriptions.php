<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//** START Create Duo Combined */
$subscriptions = $buckaroo->method('subscriptions')->manually()->createCombined([
    'pushURL' => 'https://buckaroo.dev/push',
    'includeTransaction' => false,
    'transactionVatPercentage' => 5,
    'configurationCode' => 'gfyh9fe4',
    'email' => 'test@buckaroo.nl',
    'ratePlans' => [
        'add' => [
            'startDate' => '2033-01-01',
            'ratePlanCode' => '9863hdcj',
        ],
    ],
    'phone' => [
        'mobile' => '0612345678',
    ],
    'debtor' => [
        'code' => 'johnsmith4',
    ],
//            'person'                    => [
//                'firstName'         => 'John',
//                'lastName'          => 'Do',
//                'gender'            => Gender::FEMALE,
//                'culture'           => 'nl-NL',
//                'birthDate'         => '1990-01-01'
//            ],
    'company' => [
        'culture' => 'nl-NL',
        'companyName' => 'My Company Coporation',
        'vatApplicable' => true,
        'vatNumber' => 'NL140619562B01',
        'chamberOfCommerce' => '20091741',
    ],
    'address' => [
        'street' => 'Hoofdstraat',
        'houseNumber' => '90',
        'zipcode' => '8441ER',
        'city' => 'Heerenveen',
        'country' => 'NL',
    ],
]);

$response = $buckaroo->method('ideal')->combine($subscriptions)->pay([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
    'issuer' => 'ABNANL2A',
]);
//** END Create Duo Combined */

//** START Create Triple Combined */
$subscriptions = $buckaroo->method('subscriptions')->manually()->createCombined([
    'pushURL' => 'https://buckaroo.dev/push',
    'includeTransaction' => false,
    'transactionVatPercentage' => 5,
    'configurationCode' => 'gfyh9fe4',
    'email' => 'test@buckaroo.nl',
    'ratePlans' => [
        'add' => [
            'startDate' => '2033-01-01',
            'ratePlanCode' => '9863hdcj',
        ],
    ],
    'phone' => [
        'mobile' => '0612345679',
    ],
    'debtor' => [
        'code' => 'johnsmith4',
    ],
//            'person'                    => [
//                'firstName'         => 'John',
//                'lastName'          => 'Do',
//                'gender'            => Gender::FEMALE,
//                'culture'           => 'nl-NL',
//                'birthDate'         => '1990-01-01'
//            ],
    'company' => [
        'culture' => 'nl-NL',
        'companyName' => 'My Company Coporation',
        'vatApplicable' => true,
        'vatNumber' => 'NL140619562B01',
        'chamberOfCommerce' => '20091741',
    ],
    'address' => [
        'street' => 'Hoofdstraat',
        'houseNumber' => '90',
        'zipcode' => '8441ER',
        'city' => 'Heerenveen',
        'country' => 'NL',
    ],
]);

$paypal_extra_info = $buckaroo->method('paypal')->manually()->extraInfo([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'customer' => [
        'name' => 'John Smith',
    ],
    'address' => [
        'street' => 'Hoofstraat 90',
        'street2' => 'Street 2',
        'city' => 'Heerenveen',
        'state' => 'Friesland',
        'zipcode' => '8441AB',
        'country' => 'NL',
    ],
    'phone' => [
        'mobile' => '0612345670',
    ],
]);

$response = $buckaroo->method('paypal')->combine($subscriptions)->combine($paypal_extra_info)->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
]);
//** END Create Triple Combined */
