<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Pay
$response = $buckaroo->method('creditcard')->pay([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'name' => 'visa',
]);

//Pay Encrypted
$response = $buckaroo->method('creditcard')->payEncrypted([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'name' => 'mastercard',
    'cardData' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDW
    n7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
]);

//Pay with security code
$response = $buckaroo->method('creditcard')->payWithSecurityCode([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
    'name' => 'mastercard',
    'securityCode' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDW
    n7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
]);

//Refund
$response = $buckaroo->method('creditcard')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '13FAF43579D94F5FB8119A6819XXXXXX',
    'name' => 'mastercard',
]);

//Authorize
$response = $buckaroo->method('creditcard')->authorize([
    'amountDebit' => 10,
    'invoice' => 'testinvoice 123',
    'name' => 'mastercard',
]);

//Authorize Encrypted
$response = $buckaroo->method('creditcard')->authorizeEncrypted([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'name' => 'mastercard',
    'cardData' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDW
    n7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
]);

//Authorize with security code
$response = $buckaroo->method('creditcard')->authorizeWithSecurityCode([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
    'name' => 'mastercard',
    'securityCode' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDW
    n7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
]);

//Capture
$response = $buckaroo->method('creditcard')->capture([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
    'name' => 'mastercard',
    'securityCode' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDW
    n7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
]);

//Pay Recurrent
$response = $buckaroo->method('creditcard')->payRecurrent([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'originalTransactionKey' => '6C5DBB69E74644958F8C25199514DC6C',
    'name' => 'mastercard',
    'securityCode' => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDW
    n7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z',
]);
