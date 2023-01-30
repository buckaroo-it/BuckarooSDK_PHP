<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->method('payperemail')->paymentInvitation([
    'amountDebit' => 10,
    'invoice' => 'testinvoice 123',
    'merchantSendsEmail' => false,
    'email' => 'johnsmith@gmail.com',
    'expirationDate' => carbon()->addDays()->format('Y-m-d'),
    'paymentMethodsAllowed' => 'ideal,mastercard,paypal',
    'attachment' => '',
    'customer' => [
        'gender' => Gender::FEMALE,
        'firstName' => 'John',
        'lastName' => 'Smith',
    ],
]);
