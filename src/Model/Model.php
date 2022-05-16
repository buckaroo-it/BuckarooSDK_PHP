<?php

namespace Buckaroo\Model;

abstract class Model
{
    protected $filter = ['filter', 'fillable'];

    public function __get($property)
    {
        if (property_exists($this, $property))
        {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if($this->fillable && in_array($property, $this->fillable))
        {
            $this->$property = $value;
        }

        return $this;
    }

    public function toArray() : array
    {
        return array_filter(get_object_vars($this), function($value, $key){
            return !in_array($key, $this->filter);
        }, ARRAY_FILTER_USE_BOTH);
    }
}