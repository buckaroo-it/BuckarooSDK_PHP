<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Models;

use Buckaroo\Models\Article;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\ArticleAdapter;

class Pay extends ServiceParameter
{
    protected array $groupData = [
        'articles'   => [
            'groupType' => 'Article'
        ]
    ];

    protected Recipient $billingRecipient;
    protected Recipient $shippingRecipient;

    protected bool $b2B;
    protected bool $addressesDiffer;
    protected string $customerIPAddress;
    protected float $shippingCosts;
    protected string $costCentre;
    protected string $department;
    protected string $establishmentNumber;

    protected bool $accept = true;
    protected array $articles = [];

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['billing', 'shipping', 'articles']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function billing($billing = null)
    {
        if(is_array($billing))
        {
            $this->billingRecipient =  new Recipient('Billing', $billing);
            $this->shippingRecipient = new Recipient('Shipping', $billing);
        }

        return $this->billingRecipient;
    }

    public function shipping($shipping = null)
    {
        if(is_array($shipping))
        {
            $this->addressesDiffer = true;

            $this->shippingRecipient = new Recipient('Shipping', $shipping);
        }

        return $this->shippingRecipient;
    }

    public function articles(?array $articles = null)
    {
        if(is_array($articles))
        {
            foreach($articles as $article)
            {
                $this->articles[] = new ArticleAdapter(new Article($article));
            }
        }

        return $this->articles;
    }

    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        if($key == 'articles' && is_numeric($keyCount))
        {
            return intval($keyCount) + 1;
        }

        return $this->groupData[$key]['groupKey'] ?? null;
    }
}