<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\ServiceParameter;

class MultipleInvoiceInfo extends ServiceParameter
{
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

    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        if($key == 'invoices' && is_numeric($keyCount))
        {
            return intval($keyCount) + 1;
        }

        return $this->groupData[$key]['groupKey'] ?? null;
    }
}