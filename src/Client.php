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

use Buckaroo\SDK\Helpers\CultureHeader;
use Buckaroo\SDK\Helpers\HmacHeader;
use Buckaroo\SDK\Payload\Request;
use Buckaroo\SDK\Helpers\SoftwareHeader;
use Buckaroo\SDK\Helpers\Config;
use Buckaroo\SDK\Transfer\TransferClientInterface;
use Buckaroo\SDK\Transfer\TransferCurlClient;
use Exception;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class Client
{
    public const MODE_LIVE = 'live';
    public const MODE_TEST = 'test';

    private const ENDPOINT_LIVE = "https://checkout.buckaroo.nl";
    private const ENDPOINT_TEST = "https://testcheckout.buckaroo.nl";

    private const METHOD_GET  = 'GET';
    private const METHOD_POST = 'POST';

    /**
     * @var Buckaroo\SDK\Helpers\HmacHeader
     */
    protected $hmac;

    /**
     * @var Buckaroo\SDK\Helpers\SoftwareHeader
     */
    protected $software;

    /**
     * @var Buckaroo\SDK\Helpers\CultureHeader
     */
    protected $culture;

    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Buckaroo\SDK\Transfer\TransferClientInterface
     */
    protected $transferClient;

    public function __construct(
        $params = null,
        TransferClientInterface $transferClient = null,
        LoggerInterface $logger = null
    ) {
        $this->config   = new Config($params);
        $this->hmac     = new HmacHeader($this->config);
        $this->software = new SoftwareHeader();
        $this->culture  = new CultureHeader();
        $this->logger   = $logger ?? $this->createDefaultLogger();
        $this->transferClient = $transferClient ?? new TransferCurlClient($this->logger);
    }

    public function setWebsiteKey($websiteKey)
    {
        $this->config->set('websiteKey', $websiteKey);
    }

    public function setSecretKey($secretKey)
    {
        $this->config->set('secretKey', $secretKey);
    }

    public function setMode($mode)
    {
        $this->config->set('mode', $mode);
    }

    public function getTransactionUrl()
    {
        $mode = $this->config->get('mode');
        return ($mode == self::MODE_LIVE ?
                self::ENDPOINT_LIVE :
                self::ENDPOINT_TEST) . '/' . ltrim('json/Transaction', '/')
        ;
    }

    protected function getHeaders($url, $data, $method)
    {
        return [
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json',
            $this->hmac->getHeader($url, $data, $method),
            $this->software->getHeader(),
            $this->culture->getHeader(),
        ];
    }

    public function get($url, $responseClass = 'Buckaroo\SDK\Payload\Response')
    {
        return $this->call($url, self::METHOD_GET, null, $responseClass);
    }

    public function post($url, Request $data = null, $responseClass = 'Buckaroo\SDK\Payload\Response')
    {
        return $this->call($url, self::METHOD_POST, $data, $responseClass);
    }

    protected function call(
        $url,
        $method = self::METHOD_GET,
        Request $data = null,
        $responseClass = 'Buckaroo\SDK\Payload\Response'
    ) {
        if (!$data) {
            $data = new Request();
        }

        $json = json_encode($data, JSON_PRETTY_PRINT);

        // all headers have to be set at once
        $headers = $this->getHeaders($url, $json, $method);
        $headers = array_merge($headers, $data->getHeaders());

        try {
            list($decodedResult, $curlInfo, $responseHeaders) =
                $this->transferClient->call($url, $headers, $method, $json, $responseClass);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $response = new $responseClass($decodedResult, $curlInfo, $responseHeaders);

        return $response;
    }

    protected function createDefaultLogger()
    {
        $logger = new Logger('buckaroo-sdk');
        $logger->pushHandler(new NullHandler());

        return $logger;
    }
}
