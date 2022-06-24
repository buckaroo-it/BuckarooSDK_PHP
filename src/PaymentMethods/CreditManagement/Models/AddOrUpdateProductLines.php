<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class AddOrUpdateProductLines extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['articles'];

    protected string $invoiceKey;
    protected array $articles = [];

    protected array $groupData = [
        'articles'   => [
            'groupType' => 'ProductLine'
        ]
    ];

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