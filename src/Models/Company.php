<?php

namespace Buckaroo\Models;

class Company extends Model
{
    protected string $name;
    protected string $culture;
    protected bool $vatApplicable;
    protected string $vatNumber;
    protected string $chamberOfCommerce;
}