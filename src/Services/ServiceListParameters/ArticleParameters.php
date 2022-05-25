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
        $this->serviceList->appendParameter([
            "Name"              => "Identifier",
            "Value"             => $article->identifier,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "Description",
            "Value"             => $article->description,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "VatPercentage",
            "Value"             => $article->vatPercentage,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "Quantity",
            "Value"             => $article->quantity,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ]);

        if($article->grossUnitPrice) {
            $this->serviceList->appendParameter([
                "Name"              => "GrossUnitPrice",
                "Value"             => $article->grossUnitPrice,
                "GroupType"         => "Article",
                "GroupID"           => $groupKey
            ]);
        }

        if($article->grossUnitPriceIncl) {
            $this->serviceList->appendParameter([
                "Name"              => "GrossUnitPriceIncl",
                "Value"             => $article->grossUnitPriceIncl,
                "GroupType"         => "Article",
                "GroupID"           => $groupKey
            ]);
        }

        if($article->grossUnitPriceExcl) {
            $this->serviceList->appendParameter([
                "Name"              => "GrossUnitPriceExcl",
                "Value"             => $article->grossUnitPriceExcl,
                "GroupType"         => "Article",
                "GroupID"           => $groupKey
            ]);
        }

    }
}