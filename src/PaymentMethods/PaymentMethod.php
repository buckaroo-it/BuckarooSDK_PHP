<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Models\Model;
use Buckaroo\Models\PayPayload;
use Buckaroo\Models\RefundPayload;
use Buckaroo\Models\ServiceList;
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

    protected string $payModel = PayPayload::class;
    protected string $refundModel = RefundPayload::class;

    public function __construct(
        Client $client,
        ?string $serviceCode
    ) {
        $this->client = $client;

        $this->request = new TransactionRequest;
        $this->serviceCode = $serviceCode;
    }

    public function pay(?Model $model = null): TransactionResponse
    {
        $this->setPayPayload();

        $this->setServiceList('Pay', $model);
        dd($this->request->toJson());
        //TODO
        //Create validator class that validates specific request
        //$request->validate();
        return $this->postRequest();
    }

    public function refund(?Model $model = null): TransactionResponse
    {
        $this->setRefundPayload();

        $this->setServiceList('Refund', $model);

        return $this->postRequest();
    }

    public function setPayload(array $payload)
    {
        //When custom config pass into the payload
        $this->client->config()->merge($payload);

        $this->payload = array_merge($this->client->config()->get($this->requiredConfigFields), $payload);

        return $this;
    }

    protected function postRequest(): TransactionResponse
    {
        return $this->client->post(
            $this->request,
            TransactionResponse::class
        );
    }

    protected function setPayPayload()
    {
        $payPayload = new $this->payModel($this->payload);

        $this->request->setPayload($payPayload);

        return $this;
    }

    protected function setRefundPayload()
    {
        $refundPayload = new $this->refundModel($this->payload);

        $this->request->setPayload($refundPayload);

        return $this;
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
}
