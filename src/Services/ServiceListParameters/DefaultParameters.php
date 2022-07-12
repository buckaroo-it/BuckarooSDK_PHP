<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\ServiceList;

class DefaultParameters extends ServiceListParameter
{
    public function __construct(ServiceList $serviceList)
    {
        $this->serviceList = $serviceList;
    }
}