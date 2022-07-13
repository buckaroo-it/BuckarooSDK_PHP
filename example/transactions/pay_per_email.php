<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;
use Buckaroo\Resources\Constants\Gender;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->payment('payperemail')->paymentInvitation([
    'amountDebit'           => 10,
    'invoice'               => 'testinvoice 123',
    'merchantSendsEmail'    => false,
    'email'                 => 'johnsmith@gmail.com',
    'expirationDate'        => carbon()->addDays()->format('Y-m-d'),
    'paymentMethodsAllowed' => 'ideal,mastercard,paypal',
    'attachment'            => '',
    'customer'              => [
        'gender'        => Gender::FEMALE,
        'firstName'     => 'John',
        'lastName'      => 'Smith'
    ]
]);