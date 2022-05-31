<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Model;
use Buckaroo\Model\ServiceList;

class ArticleParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        foreach($this->data as $groupKey => $article)
        {
            $groupKey += 1;

            $this->attachArticle($groupKey, $article);
        }

        return $this->serviceList;
    }

    private function attachArticle(int $groupKey, Model $article)
    {
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('articleId'), $article->articleId);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('articleDescription'), $article->articleDescription);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('articleUnitprice'), $article->articleUnitprice);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('articleQuantity'), $article->articleQuantity);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('articleVatcategory'), $article->articleVatcategory);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('identifier'), $article->identifier);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('color'), $article->color);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('unitCode'), $article->unitCode);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('brand'), $article->brand);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('manufacturer'), $article->manufacturer);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('size'), $article->size);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('description'), $article->description);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('vatPercentage'), $article->vatPercentage);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('quantity'),  $article->quantity);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('type'), $article->type);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('grossUnitPrice'), $article->grossUnitPrice);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('grossUnitPriceIncl'), $article->grossUnitPriceIncl);
        $this->appendParameter($groupKey, "Article", $article->serviceParameterKeyOf('grossUnitPriceExcl'), $article->grossUnitPriceExcl);
    }
}