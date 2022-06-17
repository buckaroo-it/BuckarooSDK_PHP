<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\ServiceParameter;

class PaymentPlan extends ServiceParameter
{
    protected string $includedInvoiceKey;
    protected string $dossierNumber;
    protected int $installmentCount;
    protected float $installmentAmount;
    protected float $initialAmount;
    protected string $startDate;
    protected string $interval;
    protected float $paymentPlanCostAmount;
    protected float $paymentPlanCostAmountVat;
    protected string $recipientEmail;
}