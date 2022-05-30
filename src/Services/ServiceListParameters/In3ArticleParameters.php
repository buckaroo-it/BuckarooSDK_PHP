<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Article;
use Buckaroo\Model\ServiceList;

class In3ArticleParameters implements ServiceListParameter
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
        $this->appendParameter($groupKey,"Code", $article->identifier);
        $this->appendParameter($groupKey,"Name", $article->description);
        $this->appendParameter($groupKey,"Quantity", $article->quantity);
        $this->appendParameter($groupKey,"Price", $article->price);
    }

    private function appendParameter(int $groupKey, string $name, $value)
    {
        if($value) {
            $this->serviceList->appendParameter([
                "Name"              => $name,
                "Value"             => $value,
                "GroupType"         => "ProductLine",
                "GroupID"           => $groupKey
            ]);
        }

        return $this;
    }
}