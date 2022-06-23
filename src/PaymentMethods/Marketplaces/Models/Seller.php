<?php

namespace Buckaroo\PaymentMethods\Marketplaces\Models;

use Buckaroo\Models\Model;

class Seller extends Model
{
    protected string $accountId;
    protected float $amount;
    protected string $description;
}