<?php

require('../bootstrap.php');

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

//Also accepts json
//Pay
$response = $buckaroo->method('giftcard')->payRedirect([
    'amountDebit' => 10,
    'invoice' => uniqid(),
    'servicesSelectableByClient' => 'westlandbon,ideal,ippies,babygiftcard,babyparkgiftcard,beautywellness,boekenbon,boekenvoordeel,designshopsgiftcard,fashioncheque,fashionucadeaukaart,fijncadeau,koffiecadeau,kokenzo,kookcadeau,nationaleentertainmentcard,naturesgift,podiumcadeaukaart,shoesaccessories,webshopgiftcard,wijncadeau,wonenzo,yourgift,vvvgiftcard,parfumcadeaukaart',
    'continueOnIncomplete' => '1',
]);


//Refund
$response = $buckaroo->method('giftcard')->refund([
    'amountCredit' => 10,
    'invoice' => 'testinvoice 123',
    'originalTransactionKey' => '50891AED2D9647668A2F48C87B3000EE',
    'name' => 'boekenbon',
]);
