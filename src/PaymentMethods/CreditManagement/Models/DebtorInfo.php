<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\Models\Debtor;
use Buckaroo\PaymentMethods\CreditManagement\Adapters\DebtorInfoServiceParametersKeysAdapter;

class DebtorInfo extends ServiceParameter
{
    protected DebtorInfoServiceParametersKeysAdapter $debtor;

    protected array $groupData = [
        'debtor'   => [
            'groupType' => 'Debtor'
        ]
    ];

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['debtor']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function debtor($debtor = null)
    {
        if(is_array($debtor))
        {
            return $this->debtor(new DebtorInfoServiceParametersKeysAdapter(new Debtor($debtor)));
        }

        if($debtor instanceof DebtorInfoServiceParametersKeysAdapter)
        {
            $this->debtor = $debtor;
        }

        return $this->debtor;
    }
}