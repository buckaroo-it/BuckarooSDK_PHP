<?php

namespace Buckaroo\Model\Adapters\ServiceParametersKeys;

use Buckaroo\Model\Article;
use Buckaroo\Model\Model;

class TinkaArticleAdapter extends Model implements ServiceParameterKeysAdapter
{
    private Article $article;

    private array $keys = [
        'grossUnitPrice'        => 'UnitGrossPrice'
    ];

    public function __construct(Article $article) {
        $this->article = $article;
    }

    public function __get($property)
    {
        if (property_exists($this->article, $property))
        {
            return $this->article->$property;
        }

        return null;
    }

    public function serviceParameterKeyOf($propertyName): string
    {
        return (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);
    }
}