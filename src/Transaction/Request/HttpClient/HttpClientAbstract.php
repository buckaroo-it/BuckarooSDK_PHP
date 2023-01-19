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

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Handlers\Logging\Subject;

abstract class HttpClientAbstract implements HttpClientInterface
{
    /**
     *
     */
    protected const TIMEOUT = 30;
    /**
     *
     */
    protected const CONNECT_TIMEOUT = 5;

    /**
     * @var Subject
     */
    protected Subject $logger;

    /**
     * @param Subject $logger
     */
    public function __construct(Subject $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $url
     * @param array $headers
     * @param string $method
     * @param string|null $data
     * @return mixed
     */
    abstract public function call(string $url, array $headers, string $method, string $data = null);

    /**
     * @param $result
     * @return array
     * @throws BuckarooException
     */
    protected function getDecodedResult($response, $result): array
    {
        $decoded_result = json_decode($result, true);

        if (is_array($decoded_result))
        {
            return $decoded_result;
        }

        throw new BuckarooException(
            $this->logger,
            'Status code: ' .
            $response->getStatusCode() .
            ' Message: ' .
            $result
        );
    }

    /**
     * @param array $headers
     * @return array
     */
    protected function convertHeadersFormat(array $headers): array
    {
        $resultHeaders = [];

        foreach ($headers as $header)
        {
            $headerName = substr($header, 0, strpos($header, ':'));
            $headerValue = substr($header, strpos($header, ':') + 2);
            $resultHeaders[$headerName] = $headerValue;
        }

        return $resultHeaders;
    }
}
