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

declare(strict_types=1);

namespace Buckaroo\Transaction\Request\HttpClient;

use Buckaroo\Exceptions\TransferException;
use Buckaroo\Handlers\Logging\Subject;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

class HttpClientGuzzle extends HttpClientAbstract
{
    /**
     * @var Subject
     */
    protected Subject $logger;

    /**
     * @param Subject $logger
     */
    public function __construct(Subject $logger)
    {
        parent::__construct($logger);

        $this->logger = $logger;

        $this->httpClient = new Client([
            RequestOptions::TIMEOUT => self::TIMEOUT,
            RequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT,
        ]);
    }

    /**
     * @param string $url
     * @param array $headers
     * @param string $method
     * @param string|null $data
     * @return array|mixed
     * @throws TransferException
     * @throws \Buckaroo\Exceptions\BuckarooException
     */
    public function call(string $url, array $headers, string $method, string $data = null)
    {
        $headers = $this->convertHeadersFormat($headers);

        $request = new Request($method, $url, $headers, $data);

        try
        {
            $response = $this->httpClient->send($request, ['http_errors' => false]);

            $result = (string) $response->getBody();

            $this->logger->info('RESPONSE HEADERS: ' . json_encode($response->getHeaders()));
            $this->logger->info('RESPONSE BODY: ' . $response->getBody());
        }
        catch (GuzzleException $e)
        {
            throw new TransferException($this->logger, "Transfer failed", 0, $e);
        }

        $result = $this->getDecodedResult($response, $result);

        return [
            $response,
            $result,
        ];
    }
}
