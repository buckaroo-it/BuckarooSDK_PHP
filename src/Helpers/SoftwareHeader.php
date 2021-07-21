<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Helpers;

class SoftwareHeader
{
    public function __construct()
    {
    }

    public function getHeader()
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
