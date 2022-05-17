<?php

namespace Buckaroo\Model;

use Buckaroo\Helpers\Arrayable;

abstract class Model implements Arrayable
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
        $array =  array_filter(get_object_vars($this), function($value, $key){
            return !in_array($key, $this->filter);
        }, ARRAY_FILTER_USE_BOTH);

        return $this->recursiveToArray($array);
    }

    private function recursiveToArray(array $array) : array
    {
        foreach($array as $key => $value)
        {
            if(is_array($value))
            {
                $array[$key] = $this->recursiveToArray($value);
            }

            if(is_a($value, Arrayable::class))
            {
                $array[$key] = $value->toArray();
            }
        }

        return $array;
    }
}