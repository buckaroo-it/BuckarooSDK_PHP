<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\ServiceList;

class DefaultParameters implements ServiceListParameter
{
    public function __construct(ServiceList $serviceList)
    {
        $this->serviceList = $serviceList;
    }

    public function data(): ServiceList
    {
        return $this->serviceList;
    }
}