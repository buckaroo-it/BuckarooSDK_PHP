<?php

namespace Buckaroo\Models;

class BankAccount extends Model
{
    protected string $iban;
    protected string $accountName;
    protected string $bic;
}