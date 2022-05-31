<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Adapters\ServiceParametersKeys\ServiceParameterKeysAdapter;
use Buckaroo\Model\ServiceList;

abstract class ServiceListParameter
{
    protected $serviceListParameter;
    protected ServiceList $serviceList;
    protected array $data;

    public function __construct(ServiceListParameter $serviceListParameter, array $data)
    {
        $this->data = $data;
        $this->serviceListParameter = $serviceListParameter;
        $this->serviceList = $this->serviceListParameter->data();
    }

    public function data(): ServiceList
    {
        return $this->serviceList;
    }

    protected function appendParameter(?int $groupKey, ?string $groupType, string $name, $value)
    {
        if(!is_null($value)) {

            if(is_callable($value)) {

                $this->serviceList->appendParameter($value($groupKey, $groupType));

                return $this;
            }

            $this->serviceList->appendParameter([
                "Name"              => $name,
                "Value"             => $value,
                "GroupType"         => $groupType,
                "GroupID"           => $groupKey
            ]);
        }

        return $this;
    }
}