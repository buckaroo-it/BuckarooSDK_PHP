<?php

namespace Buckaroo\PaymentMethods\KlarnaPay\Models;

use Buckaroo\Models\Article;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class Pay extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['articles'];

    protected Recipient $billingRecipient;
    protected Recipient $shippingRecipient;

    protected array $articles = [];

    protected array $groupData = [
        'articles'   => [
            'groupType' => 'Article'
        ]
    ];

    public function billing($billing = null)
    {
        if(is_array($billing))
        {
            $this->billingRecipient = new Recipient('Billing', $billing);
            $this->shippingRecipient = new Recipient('Shipping', $billing);
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
                $this->articles[] = new ArticleAdapter(new Article($article));
            }
        }

        return $this->articles;
    }
}