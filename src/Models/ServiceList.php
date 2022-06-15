<?php

namespace Buckaroo\Models;

use Buckaroo\Services\ServiceListParameters\{DefaultParameters, ModelParameters, ServiceListParameter};
use function PHPUnit\Framework\isEmpty;

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
        if(is_array($value) && is_array(current($value)))
        {
            foreach($value as $singleValue)
            {
                $this->appendParameter($singleValue, $key);
            }

            return $this;
        }

        if($key)
        {
            $this->parameters[$key] = $value;

            return $this;
        }

        $this->parameters[] = $value;

        return $this;
    }

    protected function decorateParameters(Model $model, ?string $groupType = null, ?int $groupKey = null)
    {
        $this->parameterService = new ModelParameters($this->parameterService, $model, $groupType, $groupKey);

        $this->iterateThroughObject($model, $model->get_object_vars());

        return $this;
    }

    protected function iterateThroughObject(Model $model, array $array, ?string $keyName = null)
    {
        foreach($array as $key => $value)
        {
            if($value instanceof Model)
            {
                $this->decorateParameters(
                    $value,
                    $model->getGroupType($keyName ?? $key),
                    $model->getGroupKey($keyName ?? $key, is_int($key)? $key : null)
                );

                continue;
            }

            if(is_array($value) && count($value))
            {
                $this->iterateThroughObject($model, $value, $key);
            }
        }

        return $this;
    }
}