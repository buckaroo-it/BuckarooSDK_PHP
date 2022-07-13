<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$subscriptions = $buckaroo->payment('subscriptions')->manually()->createCombined([
    'includeTransaction'        => false,
    'transactionVatPercentage'  => 5,
    'configurationCode'         => 'xxxxx',
    'email'                     => 'test@buckaroo.nl',
    'rate_plans'        => [
        'add'        => [
            'startDate'         => carbon()->format('Y-m-d'),
            'ratePlanCode'      => 'xxxxxx',
        ]
    ],
    'phone'                     => [
        'mobile'                => '0612345678'
    ],
    'debtor'                    => [
        'code'          => 'xxxxxx'
    ],
    'person'                    => [
        'firstName'         => 'John',
        'lastName'          => 'Do',
        'gender'            => Gender::FEMALE,
        'culture'           => 'nl-NL',
        'birthDate'         => carbon()->subYears(24)->format('Y-m-d')
    ],
    'address'           => [
        'street'        => 'Hoofdstraat',
        'houseNumber'   => '90',
        'zipcode'       => '8441ER',
        'city'          => 'Heerenveen',
        'country'       => 'NL'
    ]
]);

$response = $this->buckaroo->payment('ideal')->combine($subscriptions)->pay([
    'invoice'       => uniqid(),
    'amountDebit' => 10.10,
    'issuer' => 'ABNANL2A'
]);