<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Request\Adapters\PaymentPayloadAdapter;
use Buckaroo\Transaction\Request\Adapters\RefundPayloadAdapter;
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

    public function __construct(
        Client $client,
        ?string $serviceCode
    ) {
        $this->client = $client;

        $this->request = new TransactionRequest;
        $this->serviceCode = $serviceCode;
    }

    public function pay(): TransactionResponse
    {
        $this->request->setPayload($this->getPaymentPayload());

        $this->setPayServiceList($this->payload['serviceParameters'] ?? []);

        //TODO
        //Create validator class that validates specific request
        //$request->validate();
        return $this->postRequest();
    }

    public function refund(): TransactionResponse
    {
        $this->request->setPayload($this->getRefundPayload());

        $this->setRefundServiceList($this->payload['serviceParameters'] ?? []);

        return $this->postRequest();
    }

    public function setPayload(array $payload)
    {
        //When custom config pass into the payload
        $this->client->config()->merge($payload);

        $this->payload = array_merge($this->client->config()->get($this->requiredConfigFields), $payload);

        return $this;
    }

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function setRefundServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Refund'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function getPaymentPayload(): array
    {
        return (new PaymentPayloadAdapter(new PaymentPayload($this->payload)))->getValues();
    }

    public function getRefundPayload(): array
    {
        return (new RefundPayloadAdapter(new RefundPayload($this->payload)))->getValues();
    }

    protected function postRequest(): TransactionResponse
    {
        return $this->client->post(
            $this->request,
            TransactionResponse::class
        );
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
