<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Article;
use Buckaroo\Models\ServiceList;

class In3ArticleParameters extends ArticleParameters
{
    public function data(): ServiceList
    {
        foreach($this->data as $groupKey => $article)
        {
            $groupKey += 1;

            $this->attachArticle($groupKey, 'ProductLine', $article);
        }

        return $this->serviceList;
    }

//    private function attachArticle(int $groupKey, Article $article)
//    {
//        $this->appendParameter($groupKey, "ProductLine", "Code", $article->identifier);
//        $this->appendParameter($groupKey, "ProductLine", "Name", $article->description);
//        $this->appendParameter($groupKey, "ProductLine", "Quantity", $article->quantity);
//        $this->appendParameter($groupKey, "ProductLine", "Price", $article->price);
//    }
}