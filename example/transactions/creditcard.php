<?php

require('../bootstrap.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->payment('creditcard')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'serviceParameters' => [
        'name'          => 'visa'
    ]
]);

//Pay Encrypted
$response = $buckaroo->payment('creditcard')->payEncrypted([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'serviceParameters' => [
        'name'          => 'mastercard',
        'cardData'      => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
    ]
]);

//Pay with security code
$response = $buckaroo->payment('creditcard')->payWithSecurityCode([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'originalTransactionKey'        => '6C5DBB69E74644958F8C25199514DC6C',
    'serviceParameters' => [
        'name'          => 'mastercard',
        'securityCode'      => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
    ]
]);

//Refund
$response = $buckaroo->payment('creditcard')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey'        => '13FAF43579D94F5FB8119A6819XXXXXX',
    'serviceParameters' => [
        'name'          => 'mastercard'
    ]
]);

//Authorize
$response = $buckaroo->payment('creditcard')->authorize([
    'amountDebit' => 10,
    'invoice' => 'testinvoice 123',
    'serviceParameters' => [
        'name'          => 'mastercard'
    ]
]);

//Authorize Encrypted
$response = $buckaroo->payment('creditcard')->authorizeEncrypted([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'serviceParameters' => [
        'name'          => 'mastercard',
        'cardData'      => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
    ]
]);

//Authorize with security code
$response = $buckaroo->payment('creditcard')->authorizeWithSecurityCode([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'originalTransactionKey'        => '6C5DBB69E74644958F8C25199514DC6C',
    'serviceParameters' => [
        'name'          => 'mastercard',
        'securityCode'      => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
    ]
]);

//Capture
$response = $buckaroo->payment('creditcard')->capture([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'originalTransactionKey'        => '6C5DBB69E74644958F8C25199514DC6C',
    'serviceParameters' => [
        'name'          => 'mastercard',
        'securityCode'      => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
    ]
]);

//Pay Recurrent
$response = $buckaroo->payment('creditcard')->payRecurrent([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'originalTransactionKey'        => '6C5DBB69E74644958F8C25199514DC6C',
    'serviceParameters' => [
        'name'          => 'mastercard',
        'securityCode'      => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
    ]
]);