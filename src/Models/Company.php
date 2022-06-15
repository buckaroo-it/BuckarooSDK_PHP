<?php

namespace Buckaroo\Models;

class Company extends Person
{
    protected string $companyName;
    protected bool $vatApplicable;
    protected string $vatNumber;
    protected string $chamberOfCommerce;
}