<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Company;
use Buckaroo\Model\ServiceList;

class CompanyParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $this->serviceList = $this->serviceListParameter->data();

        $company = $this->data['company'];

        foreach($company->toArray() as $key => $value) {
            $this->appendParameter(null, "Company", $company->serviceParameterKeyOf($key), $value);
        }

        return $this->serviceList;
    }
}