<?php

namespace Buckaroo\Models;

use Buckaroo\Services\ServiceListParameters\{DefaultParameters, ModelParameters, ServiceListParameter};

class ServiceList extends Model
{
    protected int $version;
    protected string $action;
    protected string $name;
    protected array $parameters = [];
    private ServiceListParameter $parameterService;

    public function __construct(string $name, int $version, string $action, ?Model $model = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->action = $action;

        $this->parameterService = new DefaultParameters($this);

        if($model)
        {
            $this->decorateParameters($model);
            $this->parameterService->data();
        }

        parent::__construct();
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function appendParameter($value, $key = null)
    {
        /* Check value pass multiple, iterate through it*/
        if(is_array($value) && is_array(current($value))) {
            foreach($value as $singleValue)
            {
                $this->appendParameter($singleValue, $key);
            }

            return $this;
        }

        if($key) {
            $this->parameters[$key] = $value;

            return $this;
        }

        $this->parameters[] = $value;

        return $this;
    }

    protected function decorateParameters(Model $model, ?string $groupType = null, ?int $groupKey = null)
    {
        $this->parameterService = new ModelParameters($this->parameterService, $model, $groupType, $groupKey);

        foreach($model->get_object_vars() as $key => $value)
        {
            if($value instanceof Model)
            {
                $this->decorateParameters($value, $model->getGroupType($key), $model->getGroupKey($key));
            }
        }

        return $this;
    }
}