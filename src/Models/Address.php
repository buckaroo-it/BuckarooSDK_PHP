<?php

namespace Buckaroo\Models;

class Address extends Model
{
    protected string $street;
    protected string $houseNumber;
    protected string $houseNumberAdditional;
    protected string $zipcode;
    protected string $city;
    protected string $state;
    protected string $country;
}