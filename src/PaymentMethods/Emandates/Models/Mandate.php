<?php

namespace Buckaroo\PaymentMethods\Emandates\Models;

use Buckaroo\Models\ServiceParameter;

class Mandate extends ServiceParameter
{
    protected string $debtorbankid;
    protected string $debtorreference;
    protected float $sequencetype;
    protected string $purchaseid;
    protected string $mandateid;
    protected string $language;
    protected string $emandatereason;
    protected float $maxamount;
    protected string $originalMandateId;
}