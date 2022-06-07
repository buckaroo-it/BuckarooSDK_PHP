<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Customer;
use Buckaroo\Model\ServiceList;

class BankTransferCustomerParameters extends CustomerParameters
{
    public function data(): ServiceList
    {
        $customer = $this->data['customer'];

        $this->attachCustomer(null, $customer);

        return $this->serviceList;
    }
}