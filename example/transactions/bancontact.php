<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('bancontactmrcash')->pay([
    //'saveToken' => true, //SaveToken is the parameter used to indicate if a token is to be created, that can be used for Wallet Initiated Payments in the future.
    //'encryptedCardData' => '', //If this is set PayEncryoted Action will be used. The value of this parameter is the result of the "encryptCardData"-function of our Client Side Encryption SDK.
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
]);

//PayEncrypted
$response = $buckaroo->method('bancontactmrcash')->payEncrypted([
    'invoice' => uniqid(),
    'amountDebit' => 10.10,
    'description' => 'Bancontact PayEncrypted Test 123',
    'encryptedCardData' => '001SlXfd8MbiTd/JFwCiGVs3f6o4x6xt0aN29NzOSNZHPKlVsz/EWeQmyhb1gGZ86VY88DP7gfDV+UyjcPfpVfHZd7u+WkO71hnV2QfYILCBNqE1aiPv2GQVGdaGbuoQloKu1o3o3I1UDmVxivXTMQX76ovot89geA6hqbtakmpmvxeiwwea3l4htNoX1IlD1hfYkDDl9rzSu5ypcjvVs6aRGXK5iMHnyrmEsEnfdj/Q5XWbsD5xAm4u3y6J8d4UP7LB31VLECzZUTiJOtKKcCQlT01YThIkQlj8PWBBMtt4H52VN3IH2+wPYtR8HiOZzcA2HA7UxozogIpS53tIURj/g==',
]);

//Recurrent payment
$response = $buckaroo->method('bancontactmrcash')->payRecurrent([
    'invoice' => 'testinvoice 123',
    'amountDebit' => 10.50,
    'originalTransactionKey' => '91D08EC01F414926A4CA29C059XXXXXX',
]);


//Refund
$response = $buckaroo->method('bancontactmrcash')->refund([
    'invoice' => '', //Set invoice number of the transaction to refund
    'originalTransactionKey' => '', //Set transaction key of the transaction to refund
    'amountCredit' => 10.10,
]);
