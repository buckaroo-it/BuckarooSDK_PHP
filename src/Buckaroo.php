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

namespace Buckaroo;

use Buckaroo\Client;
use Buckaroo\Exceptions\SdkException;
use Buckaroo\Helpers\Base;
use Buckaroo\Model\Payload;
use Buckaroo\Payload\PaymentResult;
use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\PaymentMethodFactory;

class Buckaroo
{   
    private $websiteKey, $secretKey, $mode;

    public function __construct(string $websiteKey, string $secretKey, string $mode = null) {
        $this->websiteKey = $websiteKey;
        $this->secretKey = $secretKey;
        $this->mode = ($mode) ? $mode : $_ENV['BPE_MODE'];
        
    }

    public function getDefaultClient() : Client
    {
        $client = new Client(
            $this->websiteKey,
            $this->secretKey,
        );        
        $client->setMode($this->mode);

        return $client;
    }

    public function pay($payload) : TransactionResponse
    {
        return $this->transactionInit($payload, 'pay');
    }

    public function authorize($payload) : TransactionResponse
    {
        return $this->transactionInit($payload, 'authorize');
    }

    public function capture($payload) : TransactionResponse
    {
        return $this->transactionInit($payload, 'capture');
    }

    public function refund($payload) : TransactionResponse
    {
        return $this->transactionInit($payload, 'refund');
    }

    public function transactionInit($payload, $action) : TransactionResponse
    {
        $prepareTransaction = $this->prepareTransaction($payload);
        $paymentMethod = PaymentMethodFactory::getPaymentMethod($this->getDefaultClient(), $prepareTransaction->getMethod());
        if (in_array($action, $paymentMethod->getServiceActions()) && method_exists($paymentMethod, $action) ) {
            $response = $paymentMethod->$action($prepareTransaction);
        } else {
            $this->throwError("This payment method doesn't support ".$action." service action. Service actions supported:".explode(', ', $paymentMethod->getServiceActions()));
        }       

        return $response;
    }

    public function prepareTransaction($payload) : TransactionRequest
    {
        if (!is_array($payload)) {
            if (Base::isJson($payload)) {
                $payload = json_decode($payload, true);
            } else {
                $this->throwError("Invalid payload format. Array or json required.");
            }
        }

        $payloadSet = array_merge(Payload::getDefaultPayload(), $payload);
        $prepareTransaction = Transaction::prepare($payloadSet);

        return $prepareTransaction;
    }

    protected function throwError(string $message, $value = ''): void
    {
        throw new SdkException($this->logger, "$message: '{$value}'");
    }
}