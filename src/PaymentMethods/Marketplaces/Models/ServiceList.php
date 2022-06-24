<?php

namespace Buckaroo\PaymentMethods\Marketplaces\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class ServiceList extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['sellers'];

    protected string $daysUntilTransfer;

    protected Marketplace $marketplace;
    protected array $sellers = [];

    protected array $groupData = [
        'marketplace'   => [
            'groupType' => 'Marketplace'
        ],
        'sellers'   => [
            'groupType' => 'Seller'
        ]
    ];

    public function marketplace($marketplace = null)
    {
        if(is_array($marketplace))
        {
            $this->marketplace = new Marketplace($marketplace);
        }

        return $this->marketplace;
    }

    public function sellers($sellers = null)
    {
        if(is_array($sellers))
        {
            foreach($sellers as $seller)
            {
                $this->sellers[] = new Seller($seller);
            }
        }

        return $this->sellers;
    }
}