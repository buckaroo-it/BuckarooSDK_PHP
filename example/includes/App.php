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

declare(strict_types=1);

namespace Buckaroo\Example;

use Buckaroo\Exceptions\SdkException;
use Buckaroo\Payload\PaymentResult;
use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;
use Psr\Log\LoggerInterface;

class App
{
    protected $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function prepareTransactionRequest(): TransactionRequest
    {
        $request = new TransactionRequest();
        $request->setAmountDebit(10.10);
        $request->setInvoice(self::getOrderId());
        $request->setOrder(self::getOrderId());
        $request->setCurrency($_ENV['BPE_EXAMPLE_CURRENCY_CODE']);
        $request->setReturnURL($_ENV['BPE_EXAMPLE_RETURN_URL']);
        $request->setReturnURLCancel($_ENV['BPE_EXAMPLE_RETURN_URL']);
        $request->setPushURL($_ENV['BPE_EXAMPLE_RETURN_URL']);

        return $request;
    }

    public function handlePush(array $data, string $secretKey): PaymentResult
    {
        $result = new PaymentResult($data);
        $this->logger->debug(__METHOD__ . '| start |', [$result->getData()]);
        if ($result->isValid($secretKey)) {
            $this->logger->debug(
                __METHOD__ . '| params |',
                [
                    $result->getServiceName(),
                    $result->getStatusCode(),
                    $result->getOrder(),
                    $result->getTransactionKey()
                ]
            );

            if ($result->isSuccess()) {
                $this->logger->debug(__METHOD__ . '| success|');
            } elseif ($result->isCanceled()) {
                $this->logger->debug(__METHOD__ . '| cancelled |');
            } elseif ($result->isAwaitingConsumer()) {
                $this->logger->debug(__METHOD__ . '| awaiting consumer |');
            } elseif ($result->isPendingProcessing()) {
                $this->logger->debug(__METHOD__ . '| pending processing|');
            } elseif ($result->isWaitingOnUserInput()) {
                $this->logger->debug(__METHOD__ . '| waiting on user input |');
            }
        } else {
            throw new SdkException($this->logger, __METHOD__, "Push is invalid");
        }
        return $result;
    }

    public function handleReturn(array $data, string $secretKey)
    {
        $result = $this->handlePush($data, $secretKey);
        $this->logger->debug(__METHOD__ . ' | Response status: '. $result->getStatusCode());
        $this->logger->debug(__METHOD__ . ' | Description: '. $result->getSubCodeMessage());
    }

    public function handleResponse(TransactionResponse $response)
    {
        //$response = '';
        if ($response) {
            if ($response->hasRedirect() && $response->getRedirectUrl()) {
                if (php_sapi_name() == 'cli') {
                    $this->logger->debug(__METHOD__ . ' | Redirect to '. $response->getRedirectUrl());
                } else {
                    header('Location: ' . $response->getRedirectUrl(), true, 302);
                }
            } else {
                $this->logger->debug(__METHOD__ . ' | Response status: '. $response->getStatusCode());
                if (!$response->isSuccess() && $response->hasSomeError()) {
                    throw new SdkException(
                        $this->logger,
                        __METHOD__,
                        'Error in response: '. $response->getSomeError()
                    );
                }
            }
        } else {
            throw new SdkException($this->logger, __METHOD__, "Empty response");
        }
    }

    public function handleException(\Exception $e)
    {
        throw new SdkException($this->logger, __METHOD__, $e->getMessage());
    }

    public static function getOrderId(): string
    {
        return 'sdk_' . date('ymdHis') . rand(1, 99);
    }

}
