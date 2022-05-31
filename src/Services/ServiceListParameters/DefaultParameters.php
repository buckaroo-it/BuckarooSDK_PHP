<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\ServiceList;

class DefaultParameters extends ServiceListParameter
{
    public function __construct(ServiceList $serviceList)
    {
        $this->serviceList = $serviceList;
    }
}