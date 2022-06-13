<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Address;
use Buckaroo\Models\Person;
use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceList;

class CustomerParameters extends ServiceListParameter
{
    protected Person $person;
    protected string $groupType;

    public function __construct(ServiceListParameter $serviceListParameter, Person $person, string $groupType = 'Customer')
    {
        $this->person = $person;
        $this->groupType = $groupType;

        parent::__construct($serviceListParameter);
    }

    public function data(): ServiceList
    {
        foreach($this->person->toArray() as $key => $value) {
            $this->appendParameter(null, $this->groupType, $this->person->serviceParameterKeyOf($key), $value);
        }

//        if($customer->address) {
//            $this->attachCustomerAddress('Address', $customer->address);
//        }
//
//        if($customer->billing) {
//            $this->attachCustomerAddress('BillingCustomer', $customer->billing);
//        }
//
//        if($customer->shipping) {
//            $this->attachCustomerAddress('ShippingCustomer', $customer->shipping);
//        }

        return $this->serviceList;
    }

//    protected function attachCustomer(?string $groupType, Model $customer)
//    {
//        $customerArray = array_diff_key($customer->toArray(), array_flip(['shipping', 'billing', 'address', 'useBillingInfoForShipping']));
//
//        foreach($customerArray as $key => $value) {
//            $this->appendParameter(null, $groupType, $customer->serviceParameterKeyOf($key), $value);
//        }
//    }
//
//    protected function attachCustomerAddress(string $groupType, Model $address)
//    {
//        foreach($address->toArray() as $key => $value) {
//            $this->appendParameter(null, $groupType, $address->serviceParameterKeyOf($key), $value);
//        }
//    }
}