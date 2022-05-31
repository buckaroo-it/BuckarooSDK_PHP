<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Article;
use Buckaroo\Model\ServiceList;

class ArticleParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $this->process();

        return $this->serviceList;
    }

    private function process()
    {
        foreach($this->data as $groupKey => $article)
        {
            $groupKey += 1;

            $article = (new Article())->setProperties($article);

            $this->attachArticle($groupKey, $article);
        }
    }

    private function attachArticle(int $groupKey, Article $article)
    {
        $this->appendParameter($groupKey, "Article","ArticleId", $article->articleId);
        $this->appendParameter($groupKey, "Article","ArticleDescription", $article->articleDescription);
        $this->appendParameter($groupKey, "Article","ArticleUnitprice", $article->articleUnitprice);
        $this->appendParameter($groupKey, "Article","ArticleQuantity", $article->articleQuantity);
        $this->appendParameter($groupKey, "Article","ArticleVatcategory", $article->articleVatcategory);
        $this->appendParameter($groupKey, "Article","Identifier", $article->identifier);
        $this->appendParameter($groupKey, "Article","Color", $article->color);
        $this->appendParameter($groupKey, "Article","UnitCode", $article->unitCode);
        $this->appendParameter($groupKey, "Article","Brand", $article->brand);
        $this->appendParameter($groupKey, "Article","Manufacturer", $article->manufacturer);
        $this->appendParameter($groupKey, "Article","Size", $article->size);
        $this->appendParameter($groupKey, "Article","Description", $article->description);
        $this->appendParameter($groupKey, "Article","VatPercentage", $article->vatPercentage);
        $this->appendParameter($groupKey, "Article","Quantity",  $article->quantity);
        $this->appendParameter($groupKey, "Article","GrossUnitPrice", $article->grossUnitPrice);
        $this->appendParameter($groupKey, "Article","GrossUnitPriceIncl", $article->grossUnitPriceIncl);
        $this->appendParameter($groupKey, "Article","GrossUnitPriceExcl", $article->grossUnitPriceExcl);
    }
}