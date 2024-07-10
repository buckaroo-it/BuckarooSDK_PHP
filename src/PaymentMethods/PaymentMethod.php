<?php
/*
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

namespace Buckaroo\PaymentMethods;

use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Request\TransactionRequest;
use Buckaroo\Transaction\Response\TransactionResponse;
use Psr\Log\LoggerInterface;

/**
 *
 */
abstract class PaymentMethod implements PaymentInterface
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var string
     */
    protected string $code;
    /**
     * @var TransactionRequest
     */
    protected TransactionRequest $request;
    /**
     * @var array
     */
    protected array $payload;

    /**
     * @var array|string[]
     */
    protected array $requiredConfigFields = ['currency'];
    /**
     * @var string
     */
    protected string $paymentName = "";
    /**
     * @var int
     */
    protected int $serviceVersion = 0;

    /**
     * @var Combinable
     */
    protected Combinable $combinablePayment;
    /**
     * @var bool
     */
    protected bool $isManually = false;

    /**
     * @var string|null
     */
    protected ?string $serviceCode;

    /**
     * @param Client $client
     * @param string|null $serviceCode
     */
    public function __construct(Client $client, ?string $serviceCode)
    {
        $this->client = $client;

        $this->request = new TransactionRequest;
        $this->serviceCode = $serviceCode;
    }

    /**
     * @param array $payload
     * @return $this
     * @throws \Buckaroo\Exceptions\BuckarooException
     */
    public function setPayload(array $payload)
    {
        //When custom config pass into the payload
        $this->client->config()->merge($payload);

        $this->payload = array_merge($this->client->config()->get($this->requiredConfigFields), $payload);

        return $this;
    }

    /**
     * @return $this|mixed
     */
    protected function postRequest()
    {
        if ($this->isManually)
        {
            return $this;
        }

        return $this->client->post(
            $this->request,
            TransactionResponse::class
        );
    }

    /**
     * @return $this|mixed
     */
    protected function dataRequest()
    {
        if ($this->isManually)
        {
            return $this;
        }

        return $this->client->dataRequest(
            $this->request,
            TransactionResponse::class
        );
    }

    /**
     * @param string $action
     * @param Model|null $model
     * @return $this
     */
    protected function setServiceList(?string $action, ?Model $model = null): PaymentMethod
    {
        $serviceList = new ServiceList($this->paymentName(), $this->serviceVersion(), $action, $model);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    /**
     * @return string
     */
    public function paymentName(): string
    {
        return $this->paymentName;
    }

    /**
     * @return int
     */
    public function serviceVersion(): int
    {
        return $this->serviceVersion;
    }

    /**
     * @return PaymentInterface
     */
    public function setServiceVersion(int $serviceVersion): PaymentInterface
    {
        $this->serviceVersion = $serviceVersion;

        return $this;
    }

    /**
     * @param bool|null $isManually
     * @return $this
     */
    public function manually(?bool $isManually = null)
    {
        if ($isManually !== null)
        {
            $this->isManually = $isManually;
        }

        return $this;
    }

    /**
     * @param Combinable $combinablePayment
     * @return $this
     */
    public function combinePayment(Combinable $combinablePayment)
    {
        $this->combinablePayment = $combinablePayment;

        $payload_data = array_filter($combinablePayment->request->data(), function ($key) {
            return ! in_array($key, ['Services']);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($payload_data as $key => $value)
        {
            $this->request->setData($key, $value);
        }

        foreach ($this->combinablePayment->request->getServices()->serviceList() as $serviceList)
        {
            $this->request->getServices()->pushServiceList($serviceList);
        }

        return $this;
    }

    public function request(): TransactionRequest
    {
        return $this->request;
    }
}
