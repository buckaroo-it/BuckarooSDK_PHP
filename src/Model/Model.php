<?php

namespace Buckaroo\Model;

use Buckaroo\Resources\Arrayable;

abstract class Model implements Arrayable
{
    public function __get($property)
    {
        if (property_exists($this, $property) && isset($this->$property))
        {
            return $this->$property;
        }

        return null;
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property))
        {
            $this->$property = $value;
        }

        return $this;
    }

    public function setProperties(array $data)
    {
        if($data)
        {
            foreach($data ?? array() as $property => $value)
            {
                $this->$property = $value;
            }
        }

        return $this;
    }

    public function serviceParameterKeyOf($propertyName)
    {
        return ucfirst($propertyName);
    }

    public function toArray() : array
    {
        return $this->recursiveToArray(get_object_vars($this));
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