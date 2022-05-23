<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Client;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\AfterpayParametersService;

class KlarnaPay extends PaymentMethod
{
    public const SERVICE_VERSION = 0;

    private AfterpayParametersService $parametersService;

    public function __construct(Client $client)
    {
        $this->parametersService = new AfterpayParametersService();

        parent::__construct($client);
    }

    public function setPayServiceList(array $serviceParameters = [])
    {
        $this->parametersService->processArticles($serviceParameters['articles'] ?? []);
        $this->parametersService->processCustomer($serviceParameters['customer'] ?? []);

        $serviceList =  new ServiceList(
            self::AFTERPAY,
            self::SERVICE_VERSION,
            'Pay',
            $this->parametersService->toArray()
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function setRefundServiceList()
    {
        // TODO: Implement setRefundServiceList() method.
    }
}