<?php

namespace Buckaroo\PaymentMethods\Billink\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Billink\Adapters\ArticleServiceParametersKeysAdapter;

class Pay extends ServiceParameter
{
    protected Recipient $billingRecipient;
    protected Recipient $shippingRecipient;

    protected string $trackandtrace;
    protected string $vATNumber;

    protected array $articles = [];

    protected array $groupData = [
        'articles'   => [
            'groupType' => 'Article'
        ]
    ];

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
            $this->billingRecipient = new Recipient('Billing', $billing);
            $this->shippingRecipient = new Recipient('Billing', $billing);
        }

        return $this->billingRecipient;
    }

    public function shipping($shipping = null)
    {
        if(is_array($shipping))
        {
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
                $this->articles[] = new ArticleServiceParametersKeysAdapter(new Article($article));
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