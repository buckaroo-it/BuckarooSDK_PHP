<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Article;
use Buckaroo\Models\ServiceList;

class KlarnaKPArticleParameters extends ServiceListParameter
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
        $this->appendParameter( $groupKey,'Article', 'ArticleNumber', $article->articleNumber);
        $this->appendParameter( $groupKey,'Article', 'ArticleQuantity', $article->articleQuantity);
        $this->appendParameter( $groupKey,'Article', 'ReservationNumber', $article->reservationNumber);
    }
}