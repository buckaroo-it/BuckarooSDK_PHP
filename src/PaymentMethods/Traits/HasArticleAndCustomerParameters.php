<?php

namespace Buckaroo\PaymentMethods\Traits;

use Buckaroo\Models\Adapters\ServiceParametersKeys\Adapter;
use Buckaroo\Models\Article;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceList;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;

trait HasArticleAndCustomerParameters
{
    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $parametersService = new ArticleParameters(new DefaultParameters($serviceList), $this->articles($serviceParameters['articles'] ?? []));
        $parametersService = new CustomerParameters($parametersService, ['customer' => (new Person())->setProperties($serviceParameters['customer'] ?? [])]);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    private function articles($articles, ?string $adapter = null): array {
        return array_map(function($article) use ($adapter){
            $article =  (new Article())->setProperties($article);

            if($adapter) {
                return new $adapter($article);
            }

            return $article;
        }, $articles);
    }
}