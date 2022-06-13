<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;

class BankTransferCustomerParameters extends CustomerParameters
{
    public function data(): ServiceList
    {
        $customer = $this->data['customer'];

        $this->attachCustomer(null, $customer);

        return $this->serviceList;
    }
}