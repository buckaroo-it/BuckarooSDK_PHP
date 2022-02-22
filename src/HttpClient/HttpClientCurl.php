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

class HttpClientCurl extends HttpClientAbstract
{
    protected $logger;

    public function call(string $url, array $headers, string $method, string $data = null)
    {
        $this->logger->debug(__METHOD__, [$url, $headers, $method, !empty($data) ? json_decode($data) : '']);

        $this->checkMethod($method);

        $curl = curl_init();
        $this->setupBaseOptions($curl);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        $curlInfo = curl_getinfo($curl);

        $this->checkEmptyResult($result, curl_error($curl));

        $this->checkStatusCode(
            $result,
            (empty($curlInfo['http_code']) || $curlInfo['http_code'] != 200)
        );

        curl_close($curl);

        return $this->getDecodedResult($result);
    }

    private function setupBaseOptions($curl)
    {
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Buckaroo SDK');

        curl_setopt($curl, CURLOPT_TIMEOUT, self::TIMEOUT);

        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    }
}
