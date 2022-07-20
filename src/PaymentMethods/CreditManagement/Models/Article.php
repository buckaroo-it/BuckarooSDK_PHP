<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

class Article extends \Buckaroo\Models\Article
{
    protected string $productLine;
    protected string $productGroupName;
    protected string $productGroupOrderIndex;
    protected string $productOrderIndex;
    protected string $unitOfMeasurement;
    protected float $discountPercentage;
    protected float $totalDiscount;
    protected float $totalVat;
    protected float $totalAmountExVat;
    protected float $totalAmount;
}