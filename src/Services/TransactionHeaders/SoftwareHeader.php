<?php

declare(strict_types=1);

namespace Buckaroo\Services\TransactionHeaders;

class SoftwareHeader extends TransactionHeader
{
    public function getHeaders(): array {
        $headers = $this->transactionHeader->getHeaders();

        $headers[] = "Software: " .  json_encode([
            "PlatformName"    => "SDK",
            "PlatformVersion" => '0.0.1',
            "ModuleSupplier"  => "Buckaroo",
            "ModuleName"      => "BuckarooPayments",
            "ModuleVersion"   => '0.0.1',
        ]);

        return $headers;
    }
}
