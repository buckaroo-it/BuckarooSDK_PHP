<?php

namespace Buckaroo\PaymentMethods\Marketplaces\Models;

use Buckaroo\Models\Model;

class Marketplace extends Model
{
    protected float $amount;
    protected string $description;
}