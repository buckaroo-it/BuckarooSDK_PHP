<?php

namespace Buckaroo\Model;

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

    public function getParameters(): array
    {
        return $this->Parameters;
    }
}