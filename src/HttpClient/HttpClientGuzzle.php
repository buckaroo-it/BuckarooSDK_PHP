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

namespace Buckaroo\HttpClient;

use Buckaroo\Exceptions\TransferException;
use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class HttpClientGuzzle extends HttpClientAbstract
{
    public function __construct(LoggerInterface $logger = null)
    {
        parent::__construct($logger);
        $this->httpClient = new Client($this->getBaseOptions());
    }

    public function call(string $url, array $headers, string $method, string $data = null)
    {
        $headers = $this->convertHeadersFormat($headers);
        $this->logger->debug(__METHOD__ . '|1|', [$url, $headers, $method, !empty($data) ? json_decode($data) : '']);

        $this->checkMethod($method);

        $request = new Request($method, $url, $headers, $data);

        try {
            $response = $this->httpClient->send($request, ['http_errors' => false]);
            $result = (string) $response->getBody();
        } catch (GuzzleException $e) {
            throw new TransferException(
                $this->logger,
                __METHOD__ . '|5|',
                $e
            );
        }

        $this->checkEmptyResult($result, "empty response");

        $this->checkStatusCode(
            $result,
            (!$response->getStatusCode() || $response->getStatusCode() != 200)
        );

        return $this->getDecodedResult($result);
    }

    protected function getBaseOptions(): array
    {
        return [
            RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
            RequestOptions::TIMEOUT => self::TIMEOUT,
            RequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT
        ];
    }
}
