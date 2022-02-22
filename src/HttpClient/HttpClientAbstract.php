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

use Psr\Log\LoggerInterface;
use Buckaroo\Exceptions\TransferException;

abstract class HttpClientAbstract implements HttpClientInterface
{
    public const METHOD_GET  = 'GET';
    public const METHOD_POST = 'POST';

    public const VALID_METHODS = [
        self::METHOD_GET,
        self::METHOD_POST,
    ];

    protected const TIMEOUT = 30;
    protected const CONNECT_TIMEOUT = 5;

    protected LoggerInterface $logger;

    public function __construct(
        ?LoggerInterface $logger = null
    ) {
        $this->logger = $logger;
    }

    abstract public function call(string $url, array $headers, string $method, string $data = null);

    protected function checkMethod(string $method)
    {
        if (!in_array($method, self::VALID_METHODS)) {
            throw new TransferException(
                $this->logger,
                __METHOD__ . '|3|',
                'Invalid HTTP-Method: ' . $method
            );
        }
    }

    protected function checkEmptyResult($result, $error)
    {
        // check for curl errors
        if ($result === false) {
            throw new TransferException(
                $this->logger,
                __METHOD__ . '|5|',
                $error
            );
        }
    }

    protected function checkStatusCode($result, bool $isError)
    {
        if ($isError) {
            throw new TransferException(
                $this->logger,
                __METHOD__ . '|10|',
                var_export($result, true)
            );
        }
    }

    protected function getDecodedResult($result): array
    {
        $this->logger->debug(__METHOD__ . '| start |');

        $decodedResult = json_decode($result, true);

        // check for json_decode errors
        if ($decodedResult === null) {
            $jsonErrors = [
                JSON_ERROR_NONE      => 'No error occurred',
                JSON_ERROR_DEPTH     => 'The maximum stack depth has been reached',
                JSON_ERROR_CTRL_CHAR => 'Control character issue, maybe wrong encoded',
                JSON_ERROR_SYNTAX    => 'Syntaxerror',
            ];

            $decodingError = 'JSON decode error | ' .
                (!empty($jsonErrors[json_last_error()]) ? $jsonErrors[json_last_error()] : '') .
                ": " . print_r($result, true);

            throw new TransferException(
                $this->logger,
                __METHOD__,
                $decodingError
            );
        }

        $this->logger->debug(__METHOD__ . '| end |', $decodedResult);

        return $decodedResult;
    }

    protected function convertHeadersFormat(array $headers): array
    {
        $resultHeaders = [];
        foreach ($headers as $header) {
            $headerName = substr($header, 0, strpos($header, ':'));
            $headerValue = substr($header, strpos($header, ':') + 2);
            $resultHeaders[$headerName] = $headerValue;
        }
        return $resultHeaders;
    }
}
