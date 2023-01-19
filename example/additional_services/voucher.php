<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);


//Create voucher
$response = $buckaroo->method('buckaroovoucher')->create(
    [
        'usageType' => '2',
        'validFrom' => '2022-01-01',
        'validUntil' => '2024-01-01',
        'creationBalance' => '5',
    ]
);

//Pay
$response = $buckaroo->method('buckaroovoucher')->pay(
    [
        'amountDebit' => '10',
        'invoice' => uniqid(),
        'vouchercode' => 'vouchercode',
    ]
);


//Pay
$response = $buckaroo->method('buckaroovoucher')->payRemainder(
    [
        'amountDebit' => '10',
        'invoice' => uniqid(),
        'vouchercode' => 'vouchercode',
        'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
    ]
);


//Refund
$response = $buckaroo->method('buckaroovoucher')->refund(
    [
        'amountCredit' => 10,
        'invoice' => uniqid(),
        'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX',
    ]
);

//Get Balance
$response = $buckaroo->method('buckaroovoucher')->getBalance(
    [
        'vouchercode' => 'vouchercode',
    ]
);

//Deactivate
$response = $buckaroo->method('buckaroovoucher')->deactivate(
    [
        'vouchercode' => 'vouchercode',
    ]
);
