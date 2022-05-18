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
use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceParam;
use Buckaroo\PaymentMethods\PaymentMethodFactory;
use Buckaroo\Transaction\Request\TransactionRequest;
use Buckaroo\Transaction\Response\TransactionResponse;

abstract class Transaction
{
    protected $client;
    protected $payloadRequest;
    protected Payload $payload;

    abstract public function handle() : TransactionResponse;

    public function __construct(Client $client, $payload)
    {
        $this->client = $client;
        $this->request = new TransactionRequest;
        $this->config = new Config;
        $this->serviceParamModel = new ServiceParam($this->config);

        $this->payloadRequest = $payload;
    }

    protected function setPayload(string $class, string $adapter)
    {
        if (!is_array($this->payloadRequest))
        {
            $this->payloadRequest = json_decode($this->payloadRequest, true);
        }

        if($this->payloadRequest == null)
        {
            throw new \Exception("Invalid or empty payload. Array or json format required.");
        }

        $this->payload = new $class($this->payloadRequest);

        $this->request->setPayload(new $adapter($this->payload));

        return $this;
    }

    protected function getPaymentMethod()
    {
        return PaymentMethodFactory::get($this->client, $this->payloadRequest['method']);
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
