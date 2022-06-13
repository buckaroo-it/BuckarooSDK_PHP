<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\{Address, Company, Debtor, Email, Model, Person, Phone};

class CreateInvoice extends Model
{
    protected float $invoiceAmount;
    protected float $invoiceAmountVat;
    protected string $invoiceDate;
    protected string $dueDate;
    protected string $schemeKey;
    protected int $maxStepIndex;
    protected string $allowedServices;
    protected string $disallowedServices;
    protected string $allowedServicesAfterDueDate;
    protected string $disallowedServicesAfterDueDate;
    protected string $applyStartRecurrent;

    protected Address $address;
    protected Company $company;
    protected Person $person;
    protected Debtor $debtor;
    protected Email $email;
    protected Phone $phone;
}