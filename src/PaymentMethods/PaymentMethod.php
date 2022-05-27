<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Client;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\PayloadService;
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

    public function __construct(
        Client $client
    ) {
        $this->client = $client;
        $this->logger = $client->getLogger();

        $this->request = new TransactionRequest;
    }

    public function pay($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $this->request->setPayload($this->getPaymentPayload());

        $this->setPayServiceList($this->payload['serviceParameters'] ?? []);

        //TODO
        //Create validator class that validates specific request
        //$request->validate();
        return $this->postRequest();
    }

    public function refund($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $this->request->setPayload($this->getRefundPayload());

        $this->setRefundServiceList();

        return $this->postRequest();
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

    public function setRefundServiceList()
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
        return new ReplyHandler($this->client->config, $data);
    }
}
