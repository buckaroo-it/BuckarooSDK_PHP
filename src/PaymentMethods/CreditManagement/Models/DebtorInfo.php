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

    public function debtor($debtor = null)
    {
        if(is_array($debtor))
        {
            $this->debtor = new DebtorInfoAdapter(new Debtor($debtor));
        }

        return $this->debtor;
    }
}