<?php

namespace Buckaroo\PaymentMethods\Billink\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Billink\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class Capture extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['articles'];

    protected array $articles = [];

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