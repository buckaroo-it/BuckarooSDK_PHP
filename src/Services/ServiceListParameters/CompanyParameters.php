<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Company;
use Buckaroo\Model\ServiceList;

class CompanyParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $this->serviceList = $this->serviceListParameter->data();

        $company = (new Company())->setProperties($this->data);

        $this->appendParameter(null, "Company","Name", $company->name);
        $this->appendParameter(null, "Company","ChamberOfCommerce",  $company->chamberOfCommerce);

        return $this->serviceList;
    }
}