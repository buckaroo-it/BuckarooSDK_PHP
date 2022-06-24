<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\Debtor;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\DebtorInfoAdapter;

class DebtorInfo extends ServiceParameter
{
    protected DebtorInfoAdapter $debtor;

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
            return $this->debtor(new DebtorInfoAdapter(new Debtor($debtor)));
        }

        if($debtor instanceof DebtorInfoAdapter)
        {
            $this->debtor = $debtor;
        }

        return $this->debtor;
    }
}