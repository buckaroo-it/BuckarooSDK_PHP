<?php declare (strict_types = 1);
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
namespace Buckaroo\SDK\Transfer;

use Exception;
use Psr\Log\LoggerInterface;

class TransferCurlClient implements TransferClientInterface
{
    const METHOD_GET  = 'GET';
    const METHOD_POST = 'POST';

    protected $validMethods = [
        self::METHOD_GET,
        self::METHOD_POST,
    ];

    protected $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function call(string $url, array $headers, string $method, string $data = null)
    {
        $this->logger->info(__METHOD__ . '|1|', [$url, $headers, $method, !empty($data) ? json_decode($data) : '']);

        if (!in_array($method, $this->validMethods)) {
            throw new Exception('Invalid HTTP-Methode: ' . $method);
        }

        $curl = curl_init();

        $this->setupBaseOptions($curl);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // get response headers
        $responseHeaders = [];
        $this->getCurlHeaders($curl, $responseHeaders);

        // GET/POST
        $result = curl_exec($curl);

        $curlInfo = curl_getinfo($curl);

        // check for curl errors
        if ($result === false) {
            $this->logger->error(__METHOD__ . '|5|', [curl_error($curl)]);
            throw new Exception('Buckaroo API curl error: ' . curl_error($curl));
        }

        $this->logger->info(__METHOD__ . '|10|');

        $decodedResult = json_decode($result, true);

        // check for json_decode errors
        if ($decodedResult === null) {
            $jsonErrors = [
                JSON_ERROR_NONE      => 'No error occurred',
                JSON_ERROR_DEPTH     => 'The maximum stack depth has been reached',
                JSON_ERROR_CTRL_CHAR => 'Control character issue, maybe wrong encoded',
                JSON_ERROR_SYNTAX    => 'Syntaxerror',
            ];

            $decodingError = 'Buckaroo API json error: ' .
                (!empty($jsonErrors[json_last_error()]) ? $jsonErrors[json_last_error()] : 'JSON decode error') .
                ": " . print_r($result, true);
            $this->logger->info(__METHOD__ . '|15|', [$decodingError]);
            throw new Exception($decodingError);
        }

        curl_close($curl);

        $this->logger->info(__METHOD__ . '|20|', [$decodedResult, $curlInfo, $responseHeaders]);

        return [$decodedResult, $curlInfo, $responseHeaders];
    }

    protected function setupBaseOptions($curl)
    {
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Buckaroo SDK');

        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        //ZAK
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    }

    protected function getCurlHeaders($curl, &$headers)
    {
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$headers) {
            $length = strlen($header);
            $header = explode(':', $header, 2);

            if (count($header) < 2) // ignore invalid headers
            {
                return $length;
            }

            $name = strtolower(trim($header[0]));

            if (!array_key_exists($name, $headers)) {
                $headers[$name] = [trim($header[1])];
            } else {
                $headers[$name][] = trim($header[1]);
            }

            return $length;
        });
    }

    
}