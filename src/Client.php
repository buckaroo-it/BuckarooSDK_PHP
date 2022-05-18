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

namespace Buckaroo;

use Buckaroo\Helpers\CultureHeader;
use Buckaroo\Helpers\DefaultFactory;
use Buckaroo\Helpers\HmacHeader;
use Buckaroo\Helpers\SoftwareHeader;
use Buckaroo\HttpClient\HttpClientInterface;
use Buckaroo\Transaction\Request\Request;
use Psr\Log\LoggerInterface;

class Client
{
    public const MODE_LIVE = 'live';
    public const MODE_TEST = 'test';

    private const ENDPOINT_LIVE = 'https://checkout.buckaroo.nl';
    private const ENDPOINT_TEST = 'https://testcheckout.buckaroo.nl';

    private const METHOD_GET  = 'GET';
    private const METHOD_POST = 'POST';

    protected HmacHeader $hmac;
    protected SoftwareHeader $software;
    protected CultureHeader $culture;
    protected LoggerInterface $logger;
    protected HttpClientInterface $httpClient;

    public function __construct(
        ?string $websiteKey = '',
        ?string $secretKey = '',
        LoggerInterface $logger = null,
        HttpClientInterface $httpClient = null
    ) {
        $this->logger   = $logger ?? DefaultFactory::getDefaultLogger();
        $this->httpClient = $httpClient ?? DefaultFactory::getDefaultHttpClient($this->logger);
        $this->config   = new Config($this->logger);
        $this->hmac     = new HmacHeader($this->config);
        $this->software = new SoftwareHeader();
        $this->culture  = new CultureHeader();

        if ($websiteKey) {
            $this->setWebsiteKey($websiteKey);
        }

        if ($secretKey) {
            $this->setSecretKey($secretKey);
        }

        $this->config->setMode(self::MODE_TEST);
    }

    public function setWebsiteKey(string $websiteKey): void
    {
        $this->config->setWebsiteKey($websiteKey);
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->config->setSecretKey($secretKey);
    }

    public function setMode(string $mode): void
    {
        $this->config->setMode($mode);
    }

    public function getMode(): string
    {
        return $this->config->getMode();
    }

    public function getTransactionUrl()
    {
        return ($this->getMode() == self::MODE_LIVE ?
                self::ENDPOINT_LIVE :
                self::ENDPOINT_TEST) . '/' . ltrim('json/Transaction', '/')
        ;
    }

    protected function getHeaders(string $url, string $data, string $method): array
    {
        return [
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json',
            $this->hmac->getHeader($url, $data, $method),
            $this->software->getHeader(),
            $this->culture->getHeader(),
        ];
    }

    public function get($responseClass = 'Buckaroo\Transaction\Response\Response')
    {
        return $this->call(self::METHOD_GET, null, $responseClass);
    }

    public function post(Request $data = null, $responseClass = 'Buckaroo\Transaction\Response\TransactionResponse')
    {
        return $this->call(self::METHOD_POST, $data, $responseClass);
    }

    protected function call(
        $method = self::METHOD_GET,
        Request $data = null,
        $responseClass = 'Buckaroo\Transaction\Response\Response',
        $url = ''
    ) {
        if (!$data) {
            $data = new Request();
        }

        if (empty($url)) {
            $url = $this->getTransactionUrl();
        }

        $json = json_encode($data->toArray());

        // all headers have to be set at once
        $headers = $this->getHeaders($url, $json, $method);
        $headers = array_merge($headers, $data->getHeaders());

        $decodedResult = $this->httpClient->call($url, $headers, $method, $json, $responseClass);

        $response = new $responseClass($decodedResult);

        return $response;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
