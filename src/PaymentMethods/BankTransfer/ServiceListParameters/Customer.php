<?php

namespace Buckaroo\PaymentMethods\BankTransfer\ServiceListParameters;

use Buckaroo\Models\ServiceList;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;

class Customer extends CustomerParameters
{
    public function data(): ServiceList
    {
        $customer = $this->data['customer'];

        $this->attachCustomer(null, $customer);

        return $this->serviceList;
    }
}