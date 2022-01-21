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

use Buckaroo\Payload\PaymentResult;
use Buckaroo\Payload\TransactionResponse;
use Psr\Log\LoggerInterface;

class App
{
    protected $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function handlePush($data, $secretKey)
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
            $this->logger->debug(__METHOD__ . '| result is invalid |');
        }
        return $result;
    }

    public function handleReturn($data, $secretKey)
    {
        $result = $this->handlePush($data, $secretKey);
        $this->print('Response status: '. $result->getStatusCode());
        $this->print('Description: '. $result->getSubCodeMessage());
        //$this->print('Raw response: '. var_export($result->getData(),true));
    }

    public function handleResponse(TransactionResponse $response)
    {
        if ($response) {
            if ($response->hasRedirect() && $response->getRedirectUrl()) {
                if (php_sapi_name() == 'cli') {
                    $this->print('Redirect to '. $response->getRedirectUrl());
                } else {
                    header('Location: ' . $response->getRedirectUrl(), true, 302);
                }
            } else {
                $this->print('Response status: '. $response->getStatusCode());
                if ($response->hasSomeError()) {
                    $this->print('Description: '. $response->getSomeError());
                }
            }
        } else {
            $this->print('FAILED!');
        }
    }

    public function handleException(\Exception $e)
    {
        $this->print('ERROR: ' . $e->getMessage());
    }

    private function print($message)
    {
        echo $message . ((php_sapi_name() == 'cli') ? "\n" : "<br>");
    }

    public static function getOrderId()
    {
        return 'sdk_' . date('ymdHis') . rand(1, 99);
    }

}
