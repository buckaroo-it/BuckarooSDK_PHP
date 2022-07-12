<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\ServiceParameter;

class CreditNote extends ServiceParameter
{
    protected string $originalInvoiceNumber;
    protected string $invoiceDate;
    protected string $invoiceAmount;
    protected string $invoiceAmountVAT;
    protected string $sendCreditNoteMessage;
}