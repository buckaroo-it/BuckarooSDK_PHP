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

namespace Buckaroo\Transaction;

use Buckaroo\Config\Config;
use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Handlers\Logging\Subject;
use Buckaroo\Resources\Constants\Endpoints;
use Buckaroo\Services\TransactionHeaders\CultureHeader;
use Buckaroo\Services\TransactionHeaders\ChannelHeader;
use Buckaroo\Services\TransactionHeaders\DefaultHeader;
use Buckaroo\Services\TransactionHeaders\HmacHeader;
use Buckaroo\Services\TransactionHeaders\SoftwareHeader;
use Buckaroo\Transaction\Request\HttpClient\HttpClientFactory;
use Buckaroo\Transaction\Request\HttpClient\HttpClientInterface;
use Buckaroo\Transaction\Request\Request;
use Buckaroo\Transaction\Response\Response;
use Buckaroo\Transaction\Response\TransactionResponse;

class Client
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $httpClient;
    /**
     * @var Subject
     */
    protected Subject $logger;
    /**
     * @var Config|null
     */
    protected ?Config $config;

    /**
     * @param Config|null $config
     */
    public function __construct(?Config $config)
    {
        $this->config = $config;
        $this->httpClient = HttpClientFactory::createClient($config);
    }

    /**
     * @return string
     */
    public function getTransactionUrl(): string
    {
        return $this->getEndpoint('json/Transaction/');
    }

    /**
     * @param $path
     * @return string
     * @throws BuckarooException
     */
    public function getEndpoint($path): string
    {
        $baseUrl = ($this->config()->isLiveMode())? Endpoints::LIVE : Endpoints::TEST;

        return $baseUrl . $path;
    }

    /**
     * @param string $url
     * @param string $data
     * @param string $method
     * @return array
     */
    protected function getHeaders(string $url, string $data, string $method): array
    {
        $headers = new DefaultHeader([
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json',
        ]);

        $headers = new HmacHeader($headers, $this->config, $url, $data, $method);
        $headers = new CultureHeader($headers, $this->config);
        $headers = new ChannelHeader($headers, $this->config);
        $headers = new SoftwareHeader($headers, $this->config);

        return $headers->getHeaders();
    }

    //WIP

    /**
     * @param $responseClass
     * @return mixed
     */
    public function get($responseClass = Response::class, string $endPoint = null)
    {
        return $this->call(self::METHOD_GET, null, $responseClass, $endPoint);
    }

    /**
     * @param Request|null $data
     * @param $responseClass
     * @return mixed
     */
    public function post(Request $data = null, $responseClass = TransactionResponse::class)
    {
        return $this->call(self::METHOD_POST, $data, $responseClass);
    }

    /**
     * @param Request|null $data
     * @param $responseClass
     * @return mixed
     * @throws BuckarooException
     */
    public function dataRequest(Request $data = null, $responseClass = TransactionResponse::class)
    {
        $endPoint = $this->getEndpoint('json/DataRequest/');

        return $this->call(self::METHOD_POST, $data, $responseClass, $endPoint);
    }

    /**
     * @param Request|null $data
     * @param $responseClass
     * @return mixed
     * @throws BuckarooException
     */
    public function dataBatchRequest(Request $data = null, $responseClass = TransactionResponse::class)
    {
        $endPoint = $this->getEndpoint('json/batch/DataRequests');

        return $this->call(self::METHOD_POST, $data, $responseClass, $endPoint);
    }

    /**
     * @param Request|null $data
     * @param $responseClass
     * @return mixed
     * @throws BuckarooException
     */
    public function transactionBatchRequest(Request $data = null, $responseClass = TransactionResponse::class)
    {
        $endPoint = $this->getEndpoint('json/batch/Transactions');

        return $this->call(self::METHOD_POST, $data, $responseClass, $endPoint);
    }

    /**
     * @param Request|null $data
     * @param string $paymentName
     * @param int $serviceVersion
     * @return mixed
     * @throws BuckarooException
     */
    public function specification(Request $data = null, string $paymentName, int $serviceVersion = 0)
    {
        $endPoint = $this->getEndpoint(
            'json/Transaction/Specification/' . $paymentName .
            '?serviceVersion=' . $serviceVersion
        );

        return $this->call(self::METHOD_GET, $data, TransactionResponse::class, $endPoint);
    }

    /**
     * @param $method
     * @param Request $data
     * @param string $responseClass
     * @param string|null $endPoint
     * @return mixed
     * @throws BuckarooException
     * @throws \Buckaroo\Exceptions\TransferException
     */
    protected function call($method, Request $data = null, string $responseClass, string $endPoint = null)
    {
        $endPoint = $endPoint ?? $this->getTransactionUrl();

        // all headers have to be set at once
        $headers = $this->getHeaders($endPoint, ($data)? $data->toJson() : '', $method);
        $headers = array_merge($headers, ($data)? $data->getHeaders() : []);

        $this->config->getLogger()->info($method . ' ' . $endPoint);
        $this->config->getLogger()->info('HEADERS: ' . json_encode($headers));

        if ($data)
        {
            $this->config->getLogger()->info(
                'PAYLOAD: ' . $data->toJson()
            );
        }

        list($response, $decodedResult) = $this->httpClient->call(
            $endPoint,
            $headers,
            $method,
            ($data)? $data->toJson() : ''
        );

        $response = new $responseClass($response, $decodedResult);

        return $response;
    }

    /**
     * @param Config|null $config
     * @return Config|null
     * @throws BuckarooException
     */
    public function config(?Config $config = null)
    {
        if ($config)
        {
            $this->config = $config;
        }

        if (! $this->config)
        {
            throw new BuckarooException(
                $this->logger,
                "No config has been configured.
                 Please pass your credentials to the constructor or set up a Config object."
            );
        }

        return $this->config;
    }
}
