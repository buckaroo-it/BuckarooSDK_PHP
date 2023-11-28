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

namespace Buckaroo\Services;

use Exception;
use SimpleXMLElement;
use Buckaroo\Models\ServiceList;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Request\TransactionRequest;

class ActiveSubscriptions
{

    private const SERVICE_CODE_AND_ACTION = 'GetActiveSubscriptions';

    private const VERSION_ZERO = 0;

    private const SERVICE_PARAM_KEY = 'activesubscriptions';

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get(): array
    {
        try {
            $xmlString = $this->client
                ->dataRequest($this->buildTransaction())
                ->getServiceParameters()[self::SERVICE_PARAM_KEY] ?? null;

            if (!is_string($xmlString)) {
                return [];
            }

            $xml = new SimpleXMLElement($xmlString, LIBXML_NOCDATA);

            return $this->format(
                $xml->xpath('/ArrayOfServiceCurrencies/ServiceCurrencies')
            );
        } catch (Exception $e) {
            return [];
        }
    }

    private function buildTransaction(): TransactionRequest
    {
        $transaction = new TransactionRequest();

        $transaction
            ->getServices()
            ->pushServiceList(
                new ServiceList(
                    self::SERVICE_CODE_AND_ACTION,
                    self::VERSION_ZERO,
                    self::SERVICE_CODE_AND_ACTION
                )
            );
        return $transaction;
    }

    private function format($data): array
    {
        $decoded =  json_decode(json_encode($data), true);
        if (!is_array($decoded)) {
            return [];
        }

        $formated = [];
        foreach ($decoded as $subscription) {
            $formatedSubscription = [];
            foreach ($subscription as $key => $subscriptionData) {
                $camelKey = lcfirst($key);
                $formatedSubscription[$camelKey] = $this->formatValue($camelKey, $subscriptionData);
            }
            $formated[] = $formatedSubscription;
        }
        return $formated;
    }

    /**
     * Format value for currency
     *
     * @param string $key
     * @param string|array $value
     *
     * @return string|array
     */
    private function formatValue($key, $value)
    {
        if ($key === 'currencies') {
            $value = $value["string"];
            if (is_string($value)) {
                $value = [$value];
            }
        }

        return $value;
    }
}
