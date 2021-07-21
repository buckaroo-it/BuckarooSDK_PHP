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

namespace Buckaroo\SDK;

use Buckaroo\SDK\Client;
use Buckaroo\SDK\Buckaroo\Payload\TransactionRequest;
use Buckaroo\SDK\Helpers\Helpers;

class Transaction
{
    public static function create(Client $buckarooClient, $options = array())
    {
        $request = new TransactionRequest();

        if (isset($options['serviceName'])) {
            $request->setServiceName($options['serviceName']);
        }

        if (isset($options['serviceVersion'])) {
            $request->setServiceVersion($options['serviceVersion']);
        }

        if (isset($options['amountCredit'])) {
            $request->setAmountCredit($options['amountCredit']);
        }

        if (isset($options['amountDebit'])) {
            $request->setAmountDebit($options['amountDebit']);
        }

        if (isset($options['invoice'])) {
            $request->setInvoice($options['invoice']);
        }

        if (isset($options['order'])) {
            $request->setOrder($options['order']);
        }

        if (isset($options['currency'])) {
            $request->setCurrency($options['currency']);
        }

        if (isset($options['returnURL'])) {
            $request->setReturnURL($options['returnURL']);
        }

        if (isset($options['returnURLCancel'])) {
            $request->setReturnURLCancel($options['returnURLCancel']);
        }

        if (isset($options['pushURL'])) {
            $request->setPushURL($options['pushURL']);
        }

        if (isset($options['clientIP'])) {
            $request->setClientIP($options['clientIP']);
        }

        if (isset($options['issuer'])) {
            $request->setServiceParameter('issuer', $options['issuer']);
        }

        if (isset($options['serviceParameters'])) {
            foreach ($options['serviceParameters'] as $item) {
                $request->setServiceParameter(
                    $item['name'],
                    $item['value'],
                    $item['groupType'] ?? null,
                    $item['groupId'] ?? null
                );
            }
        }

        try {
            return $buckarooClient->post(
                $buckarooClient->getTransactionUrl(),
                $request,
                'Buckaroo\SDK\Buckaroo\Payload\TransactionResponse'
            );
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public static function push($options = array())
    {
        if (isset($options['secretKey']) && isset($options['post'])) {
            if (Helpers::validateSignature($options['post'], $options['secretKey'])) {
                return $options['post'];
            }
        }
        return ['error' => 'Data not valid'];
    }
}
