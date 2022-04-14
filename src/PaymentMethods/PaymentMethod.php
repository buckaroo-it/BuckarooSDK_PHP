<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Client;
use Buckaroo\Exceptions\SdkException;
use Buckaroo\Model\Config;
use Buckaroo\Model\RequestValidator;
use Buckaroo\Model\ServiceParam;
use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;
use Psr\Log\LoggerInterface;

abstract class PaymentMethod
{
    protected LoggerInterface $logger;
    protected Client $client;

    protected string $code;
    protected TransactionRequest $request;

    public const AFTERPAY = 'afterpay';
    public const KLARNAKP = 'klarnakp';
    public const KLARNA = 'klarna';
    public const SEPA = 'sepadirectdebit';
    public const KBC = 'kbcpaymentbutton';
    public const PAYPAL = 'paypal';
    public const EPS = 'eps';
    public const SOFORT = 'sofortueberweisung';
    public const PAYCONIQ = 'payconiq';
    public const P24 = 'przelewy24';
    public const IDEAL = 'ideal';
    public const CAPAYABLE = 'capayable';
    public const GIROPAY = 'giropay';
    public const GIFTCARD = 'giftcard';
    public const TRANSFER = 'transfer';
    public const RTP = 'requesttopay';
    public const APPLEPAY = 'applepay';
    public const ALIPAY = 'alipay';
    public const WECHATPAY = 'wechatpay';
    public const BILLINK = 'billink';
    public const BELFIUS = 'belfius';

    public function __construct(
        Client $client,
        Config $config,
        ServiceParam $serviceParam,
        RequestValidator $requestValidator
    ) {
        $this->client = $client;
        $this->config = $config;
        $this->serviceParam = $serviceParam;
        $this->requestValidator = $requestValidator;
        $this->logger = $client->getLogger();
    }

    abstract public function getCode(): string;

    public function pay(TransactionRequest $request): TransactionResponse
    {
        $request->setMethod($this->getCode());
        $request->setServiceAction('Pay');

        $this->validatePayRequest($request);

        return $this->client->post(
            $request,
            'Buckaroo\Payload\TransactionResponse'
        );
    }

    public function authorize(TransactionRequest $request): TransactionResponse
    {
        $request->setMethod($this->getCode());
        $request->setServiceAction('Authorize');

        $this->validatePayRequest($request);

        return $this->client->post(
            $request,
            'Buckaroo\Payload\TransactionResponse'
        );
    }

    public function capture(TransactionRequest $request): TransactionResponse
    {
        $request->setMethod($this->getCode());
        $request->setServiceAction('Capture');

        $this->validateCaptureRequest($request);

        return $this->client->post(
            $request,
            'Buckaroo\Payload\TransactionResponse'
        );
    }

    public function refund(TransactionRequest $request): TransactionResponse
    {
        $request->setMethod($this->getCode());
        $request->setServiceAction('Refund');

        $this->validateRefundRequest($request);

        return $this->client->post(
            $request,
            'Buckaroo\Payload\TransactionResponse'
        );
    }

    protected function validatePayRequest(TransactionRequest $request): void
    {
        if (!$request->getMethod()) {
            $this->throwError(__METHOD__, "Empty method name");
        }

        if (!$request->getAmountDebit()) {
            $this->throwError(__METHOD__, "Empty amount");
        }

        if (!$request->getInvoice()) {
            $this->throwError(__METHOD__, "Empty invoice");
        }
    }

    protected function validateCaptureRequest(TransactionRequest $request): void
    {
        if (!$request->getMethod()) {
            $this->throwError(__METHOD__, "Empty method name");
        }

        if (!$request->getOriginalTransactionKey()) {
            $this->throwError(__METHOD__, "Empty original transaction key");
        }

        if (!$request->getAmountDebit()) {
            $this->throwError(__METHOD__, "Empty amount");
        }

        if (!$request->getInvoice()) {
            $this->throwError(__METHOD__, "Empty invoice");
        }
    }

    protected function validateRefundRequest(TransactionRequest $request): void
    {
        if (!$request->getMethod()) {
            $this->throwError(__METHOD__, "Empty method name");
        }

        if (!$request->getOriginalTransactionKey()) {
            $this->throwError(__METHOD__, "Empty original transaction key");
        }

        if (!$request->getAmountCredit()) {
            $this->throwError(__METHOD__, "Empty amount");
        }

        if (!$request->getInvoice()) {
            $this->throwError(__METHOD__, "Empty invoice");
        }
    }


    protected function throwError(string $message, $value = ''): void
    {
        throw new SdkException($this->logger, "$message: '{$value}'");
    }
}
