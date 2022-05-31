<?php

namespace Buckaroo\Model;

class iDealQR extends Model
{
    protected float $amount;
    protected bool $amountIsChangeable;
    protected string $purchaseId;
    protected string $description;
    protected bool $isOneOff;
    protected string $expiration;
    protected bool $isProcessing;
    protected float $minAmount;
    protected float $maxAmount;
    protected int $imageSize;
}