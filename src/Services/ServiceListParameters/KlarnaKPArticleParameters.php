<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Article;
use Buckaroo\Model\ServiceList;

class KlarnaKPArticleParameters implements ServiceListParameter
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
            "Name"              => "ArticleNumber",
            "Value"             => $article->articleNumber,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "ArticleQuantity",
            "Value"             => $article->articleQuantity,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ]);

        $this->serviceList->appendParameter([
            "Name"              => "ReservationNumber",
            "Value"             => $article->reservationNumber,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ]);
    }
}