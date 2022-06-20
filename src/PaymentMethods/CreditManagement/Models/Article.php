<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

class Article extends \Buckaroo\Models\Article
{
    protected float $totalVat;
    protected float $totalAmount;
}