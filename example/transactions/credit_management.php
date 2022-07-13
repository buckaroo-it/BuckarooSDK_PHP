<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$invoice = $buckaroo->payment('credit_management')->manually()->createCombinedInvoice($this->invoice());

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