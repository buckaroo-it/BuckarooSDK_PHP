<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */


namespace Buckaroo\Transaction;

use Buckaroo\Client;
use Buckaroo\Model\Config;
use Buckaroo\Model\Payload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Model\ServiceParam;
use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;
use Buckaroo\PaymentMethods\PaymentMethodFactory;

abstract class Transaction
{
    protected $client;
    protected Payload $payload;

    abstract public function handle() : TransactionResponse;

    public function __construct(Client $client, array $payload)
    {
        $this->client = $client;
        $this->request = new TransactionRequest;
        $this->config = new Config;
        $this->serviceParamModel = new ServiceParam($this->config);

        $this->setPayload($payload);
        $this->prepare();
    }

    private function setPayload($payload)
    {
        if (!is_array($payload))
        {
            $payload = json_decode($payload, true);
        }

        if($payload == null)
        {
            throw new \Exception("Invalid or empty payload. Array or json format required.");
        }

        $this->payload = new Payload($payload);

        return $this;
    }

    private function prepare()
    {
        $this->request->getServices()->pushServiceList($this->getServiceList());
        $this->request->setPayload($this->payload);

        return $this;
    }

    private function getServiceList() : ServiceList
    {
        $parameters = [
            'name' => 'issuer',
            'Value' => $this->payload->issuer
        ];

        return new ServiceList(
            $this->payload->method,
            $this->payload->serviceVersion,
            $this->payload->serviceAction,
            $parameters
        );
    }

    protected function getPaymentMethod()
    {
        return PaymentMethodFactory::get($this->client, $this->payload->method);
    }

//    public static function create(Client $buckarooClient, $options = array())
//    {
//        $request = self::prepare($options);
//
//        return $buckarooClient->post(
//            $request,
//            'Buckaroo\Payload\TransactionResponse'
//        );
//    }
//
//    public static function push($options = array())
//    {
//        if (isset($options['secretKey']) && isset($options['post'])) {
//            if (Base::validateSignature($options['post'], $options['secretKey'])) {
//                return $options['post'];
//            }
//        }
//        return ['error' => 'Data not valid'];
//    }
}
