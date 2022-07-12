<?php

namespace Buckaroo\PaymentMethods\Subscriptions\Models;

use Buckaroo\Models\Model;

class Charge extends Model
{
    protected string $ratePlanChargeGuid;
    protected string $ratePlanChargeCode;

    protected float $baseNumberOfUnits;
    protected float $pricePerUnit;

    protected float $vatPercentage;
}