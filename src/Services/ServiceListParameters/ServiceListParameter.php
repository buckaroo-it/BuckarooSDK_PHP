<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\ServiceList;

interface ServiceListParameter
{
    public function data(): ServiceList;
}