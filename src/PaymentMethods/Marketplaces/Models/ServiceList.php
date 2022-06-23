<?php

namespace Buckaroo\PaymentMethods\Marketplaces\Models;

use Buckaroo\Models\ServiceParameter;

class ServiceList extends ServiceParameter
{
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

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['marketplace', 'sellers']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function marketplace($marketplace = null)
    {
        if(is_array($marketplace))
        {
            return $this->marketplace(new Marketplace($marketplace));
        }

        if($marketplace instanceof Marketplace)
        {
            $this->marketplace = $marketplace;
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

    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        if($key == 'sellers' && is_numeric($keyCount))
        {
            return intval($keyCount) + 1;
        }

        return $this->groupData[$key]['groupKey'] ?? null;
    }
}