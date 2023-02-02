<?php

namespace Buckaroo\PaymentMethods\Subscriptions\Models;

use Buckaroo\Models\ServiceParameter;

class Configuration extends ServiceParameter
{
    protected string $name;
    protected string $schemeKey;
    protected string $invoiceNumberPrefix;
    protected string $invoiceDescriptionFormat;
    protected int $dueDateDays;
    protected string $allowedServices;
    protected bool $generateInvoiceSpecification;
    protected bool $skipPayPerEmail;
}