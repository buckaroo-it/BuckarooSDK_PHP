<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceList;

class ModelParameters extends ServiceListParameter
{
    protected Model $model;
    protected string $groupType;
    protected ?int $groupKey;

    public function __construct(ServiceListParameter $serviceListParameter, Model $model, string $groupType = '', ?int $groupKey = null)
    {
        $this->model = $model;
        $this->groupType = $groupType;
        $this->groupKey = $groupKey;

        parent::__construct($serviceListParameter);
    }

    public function data(): ServiceList
    {
        foreach($this->model->toArray() as $key => $value)
        {
            if(!is_array($value))
            {
                $this->appendParameter($this->groupKey, $this->groupType, $this->model->serviceParameterKeyOf($key), $value);
            }
        }

        return $this->serviceList;
    }
}