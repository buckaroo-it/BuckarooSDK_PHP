<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceList;

class ArticleParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        foreach($this->data as $groupKey => $article)
        {
            $groupKey += 1;

            $this->attachArticle($groupKey, 'Article', $article);
        }

        return $this->serviceList;
    }

    protected function attachArticle(int $groupKey, string $groupType, Model $article)
    {
        foreach($article->toArray() as $key => $value)
        {
            $this->appendParameter($groupKey, $groupType, $article->serviceParameterKeyOf($key), $value);
        }
    }
}