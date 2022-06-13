<?php

namespace Buckaroo\Models;

class ServiceList extends Model
{
    protected $Version, $Action, $Name;
    protected array $Parameters = [];

    public function __construct(string $name, int $version, string $action, array $parameters = [])
    {
        $this->Name = $name;
        $this->Version = $version;
        $this->Action = $action;
        $this->Parameters = $parameters ?? [];
    }

    public function parameters(): array
    {
        return $this->Parameters;
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
            $this->Parameters[$key] = $value;

            return $this;
        }

        $this->Parameters[] = $value;

        return $this;
    }
}