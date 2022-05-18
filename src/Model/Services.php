<?php

namespace Buckaroo\Model;

class Services extends Model
{
    protected array $ServiceList = [];

    public function getServiceList(): array
    {
        return $this->ServiceList;
    }

    public function pushServiceList(ServiceList $serviceList): self
    {
        $this->ServiceList[] = $serviceList;

        return $this;
    }
}