<?php

namespace Buckaroo\Models;

class Email extends Model
{
    protected string $email;

    public function __construct(?string $email = null)
    {
        parent::__construct(['email' => $email]);
    }
}