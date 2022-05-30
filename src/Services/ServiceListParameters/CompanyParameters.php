<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Company;
use Buckaroo\Model\ServiceList;

class CompanyParameters implements ServiceListParameter
{
    protected $serviceListParameter;
    protected ServiceList $serviceList;
    protected array $data;

    public function __construct(ServiceListParameter $serviceListParameter, array $data)
    {
        $this->data = $data;
        $this->serviceListParameter = $serviceListParameter;
    }

    public function data(): ServiceList
    {
        $this->serviceList = $this->serviceListParameter->data();

        $company = (new Company())->setProperties($this->data);

        $this->appendParameter("Name", $company->name);
        $this->appendParameter("ChamberOfCommerce", $company->chamberOfCommerce);

        return $this->serviceList;
    }

    private function appendParameter(string $name, $value)
    {
        if($value) {
            $this->serviceList->appendParameter([
                "Name"              => $name,
                "Value"             => $value,
                "GroupType"         => "Company",
                "GroupID"           => ""
            ]);
        }

        return $this;
    }
}