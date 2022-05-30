<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Article;
use Buckaroo\Model\ServiceList;

class ArticleParameters implements ServiceListParameter
{
    protected $serviceListParameter;
    protected ServiceList $serviceList;
    protected array $data;

    public function __construct(ServiceListParameter $serviceListParameter, array $data)
    {
        $this->data = $data;
        $this->serviceListParameter = $serviceListParameter;
    }

    public function data(): ServiceList
    {
        $this->serviceList = $this->serviceListParameter->data();

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
        dd($article->grossUnitPrice);
        $this->appendParameter($groupKey,"ArticleId", $article->articleId);
        $this->appendParameter($groupKey,"ArticleDescription", $article->articleDescription);
        $this->appendParameter($groupKey,"ArticleUnitprice", $article->articleUnitprice);
        $this->appendParameter($groupKey,"ArticleQuantity", $article->articleQuantity);
        $this->appendParameter($groupKey,"ArticleVatcategory", $article->articleVatcategory);
        $this->appendParameter($groupKey,"Identifier", $article->identifier);
        $this->appendParameter($groupKey,"Color", $article->color);
        $this->appendParameter($groupKey,"UnitCode", $article->unitCode);
        $this->appendParameter($groupKey,"Brand", $article->brand);
        $this->appendParameter($groupKey,"Manufacturer", $article->manufacturer);
        $this->appendParameter($groupKey,"Size", $article->size);
        $this->appendParameter($groupKey,"Description", $article->description);
        $this->appendParameter($groupKey,"VatPercentage", $article->vatPercentage);
        $this->appendParameter($groupKey,"Quantity",  $article->quantity);
        $this->appendParameter($groupKey,"GrossUnitPrice", $article->grossUnitPrice);
        $this->appendParameter($groupKey,"GrossUnitPriceIncl", $article->grossUnitPriceIncl);
        $this->appendParameter($groupKey,"GrossUnitPriceExcl", $article->grossUnitPriceExcl);
    }

    private function appendParameter(int $groupKey, string $name, $value)
    {
        if($value) {
            $this->serviceList->appendParameter([
                "Name"              => $name,
                "Value"             => $value,
                "GroupType"         => "Article",
                "GroupID"           => $groupKey
            ]);
        }

        return $this;
    }
}