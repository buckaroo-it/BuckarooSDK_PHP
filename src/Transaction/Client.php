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

namespace Buckaroo\Transaction;

use Buckaroo\Handlers\Logging\Loggable;
use Buckaroo\Handlers\Logging\Subject;
use Buckaroo\Resources\Constants\Endpoints;
use Buckaroo\Services\TransactionHeaders\CultureHeader;
use Buckaroo\Services\TransactionHeaders\DefaultHeader;
use Buckaroo\Services\TransactionHeaders\HmacHeader;
use Buckaroo\Services\TransactionHeaders\SoftwareHeader;
use Buckaroo\Transaction\Request\HttpClient\HttpClientGuzzle;
use Buckaroo\Transaction\Request\HttpClient\HttpClientInterface;
use Buckaroo\Transaction\Request\Request;
use Buckaroo\Transaction\Response\Response;
use Buckaroo\Transaction\Response\TransactionResponse;

class Client implements Loggable
{
    private const METHOD_GET  = 'GET';
    private const METHOD_POST = 'POST';

    protected HttpClientInterface $httpClient;
    protected Subject $logger;

    public function __construct(Config $config) {
        $this->config = $config;
        $this->httpClient =  new HttpClientGuzzle();
    }

    public function getTransactionUrl(): string {
        return $this->getEndpoint('json/Transaction/');
    }

//    public function getDataRequestUrl(): string {
//        return $this->getEndpoint('json/DataRequest/');
//    }

    private function getEndpoint($path): string {
        $baseUrl = ($this->config->isLiveMode())? Endpoints::LIVE : Endpoints::TEST;

        return $baseUrl . $path;
    }

    protected function getHeaders(string $url, string $data, string $method): array {
        $headers = new DefaultHeader([
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json'
        ]);

        $headers = new HmacHeader($headers, $this->config, $url, $data, $method);
        $headers = new CultureHeader($headers);
        $headers = new SoftwareHeader($headers);

        return $headers->getHeaders();
    }


    //WIP
    public function get($responseClass = Response::class) {
        return $this->call(self::METHOD_GET, null, $responseClass);
    }

    public function post(Request $data = null, $responseClass = TransactionResponse::class) {
        return $this->call(self::METHOD_POST, $data, $responseClass);
    }

    public function dataRequest(Request $data = null, $responseClass =  TransactionResponse::class) {
        $endPoint = $this->getEndpoint('json/DataRequest/');

        return $this->call(self::METHOD_POST, $data, $responseClass, $endPoint);
    }

    protected function call($method, Request $data, string $responseClass, string $endPoint = null) {

        $endPoint = $endPoint ?? $this->getTransactionUrl();

        // all headers have to be set at once
        $headers = $this->getHeaders($endPoint, $data->toJson(), $method);
        $headers = array_merge($headers, $data->getHeaders());

        $decodedResult = $this->httpClient->call($endPoint, $headers, $method, $data->toJson(), $responseClass);

        $response = new $responseClass($decodedResult);

        return $response;
    }

    public function setLogger(Subject $logger)
    {
        $this->logger = $logger;

        $this->httpClient->setLogger($logger);

        return $this;
    }

    public function getLogger(): ?Subject
    {
        return $this->logger;
    }
}
