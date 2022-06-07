<?php

namespace Buckaroo\Model\Adapters\ServiceParametersKeys;

use Buckaroo\Model\Model;

abstract class Adapter extends Model implements ServiceParameterKeysAdapter
{
    private Model $model;
    protected array $keys;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function __get($property)
    {
        if (property_exists($this->model, $property))
        {
            return $this->model->$property;
        }

        return null;
    }

    public function serviceParameterKeyOf($propertyName): string
    {
        return (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);
    }

    public function toArray(): array
    {
        return $this->model->toArray();
    }
}