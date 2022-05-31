<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\Article;
use Buckaroo\Model\ServiceList;

class In3ArticleParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        foreach($this->data as $groupKey => $article)
        {
            $groupKey += 1;

            $article = (new Article())->setProperties($article);

            $this->attachArticle($groupKey, $article);
        }

        return $this->serviceList;
    }

    private function attachArticle(int $groupKey, Article $article)
    {
        $this->appendParameter($groupKey, "ProductLine", "Code", $article->identifier);
        $this->appendParameter($groupKey, "ProductLine", "Name", $article->description);
        $this->appendParameter($groupKey, "ProductLine", "Quantity", $article->quantity);
        $this->appendParameter($groupKey, "ProductLine", "Price", $article->price);
    }
}