<?php

require_once __DIR__ . '/../bootstrap.php';

use Buckaroo\BuckarooClient;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

try {
    foreach ($buckaroo->getActiveSubscriptions() as $subscription) {
        $availableCurrencies = implode(",", $subscription['currencies']);
        echo "serviceCode: " . $subscription['serviceCode'] . "\n";
        echo  "- available currencies: " . $availableCurrencies . "\n";
    }
} catch (\Throwable $th) {
    var_dump($th);
}
