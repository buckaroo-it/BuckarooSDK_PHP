<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Request\TransactionRequest;
use Buckaroo\Transaction\Response\TransactionResponse;
use Psr\Log\LoggerInterface;

abstract class PaymentMethod implements PaymentInterface
{
    protected LoggerInterface $logger;
    protected Client $client;

    protected string $code;
    protected TransactionRequest $request;
    protected array $payload;

    protected array $requiredConfigFields = ['currency', 'pushURL'];
    protected string $paymentName = "";
    protected int $serviceVersion = 0;

    protected Combinable $combinablePayment;
    protected bool $isManually = false;

    public function __construct(
        Client $client,
        ?string $serviceCode
    ) {
        $this->client = $client;

        $this->request = new TransactionRequest;
        $this->serviceCode = $serviceCode;
    }

    public function setPayload(array $payload)
    {
        //When custom config pass into the payload
        $this->client->config()->merge($payload);

        $this->payload = array_merge($this->client->config()->get($this->requiredConfigFields), $payload);

        return $this;
    }

    protected function postRequest()
    {
        if($this->isManually)
        {
            return $this;
        }

        return $this->client->post(
            $this->request,
            TransactionResponse::class
        );
    }

    protected function dataRequest()
    {
        if($this->isManually)
        {
            return $this;
        }

        return $this->client->dataRequest(
            $this->request,
            TransactionResponse::class
        );
    }

    protected function setServiceList(string $action, ?Model $model = null)
    {
        $serviceList = new ServiceList($this->paymentName(),  $this->serviceVersion(), $action, $model);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function handleReply(array $data): ReplyHandler
    {
        return new ReplyHandler($this->client->config(), $data);
    }

    public function paymentName(): string
    {
        return $this->paymentName;
    }

    public function serviceVersion(): int
    {
        return $this->serviceVersion;
    }

    public function manually(?bool $isManually = null)
    {
        if($isManually !== null)
        {
            $this->isManually = $isManually;
        }

        return $this;
    }

    public function combinePayment(Combinable $combinablePayment)
    {
        $this->combinablePayment = $combinablePayment;

        $payload_data = array_filter($combinablePayment->request->data(), function($key){
            return !in_array($key, ['Services']);
        }, ARRAY_FILTER_USE_KEY );

        foreach($payload_data as $key => $value)
        {
            $this->request->setData($key, $value);
        }

        foreach($this->combinablePayment->request->getServices()->serviceList() as $serviceList)
        {
            $this->request->getServices()->pushServiceList($serviceList);
        }

        return $this;
    }
}
