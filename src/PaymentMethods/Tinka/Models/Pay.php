<?php

namespace Buckaroo\PaymentMethods\Tinka\Models;

use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class Pay extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['articles'];

    protected CustomerAdapter $customer;
    protected Recipient $billingRecipient;
    protected Recipient $shippingRecipient;

    protected string $paymentMethod;
    protected string $deliveryMethod;
    protected string $deliveryDate;

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

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            $this->customer = new CustomerAdapter(new Person($customer));
        }

        return $this->customer;
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