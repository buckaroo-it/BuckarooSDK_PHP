<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class MultipleInvoiceInfo extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['invoices'];

    protected array $invoices = [];

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['invoices']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function invoices(?array $invoices = null)
    {
        if(is_array($invoices))
        {
            foreach($invoices as $invoice)
            {
                $this->invoices[] = new Invoice($invoice);
            }
        }

        return $this->invoices;
    }
}