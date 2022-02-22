<?php

declare(strict_types=1);

namespace Buckaroo\Helpers;

class SoftwareHeader
{
    public function getHeader(): string
    {
        return "Software: " . json_encode([
            "PlatformName"    => "SDK",
            "PlatformVersion" => '0.0.1',
            "ModuleSupplier"  => "Buckaroo",
            "ModuleName"      => "BuckarooPayments",
            "ModuleVersion"   => '0.0.1',
        ]);
    }
}
