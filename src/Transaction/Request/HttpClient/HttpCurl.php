<?php

namespace Buckaroo\Transaction\Request\HttpClient;

class HttpCurl implements HttpClientInterface
{

    const DEFAULT_TIMEOUT = 10;
    const DEFAULT_CONNECT_TIMEOUT = 2;
    const HTTP_NO_CONTENT = 204;
    const MAX_RETRIES = 5;
    const DELAY_INCREASE_MS = 1000;

    public function call(string $url, array $headers, string $method, string $data = null)
    {
        for ($i = 0; $i <= self::MAX_RETRIES; $i++) {
            usleep($i * self::DELAY_INCREASE_MS);

            try {
                return $this->attemptCall($method, $url, $headers, $data);
            } catch (CurlConnectTimeoutException $e) {
                // Nothing
            }
        }
    }

    private function attemptCall($method, $url, $headers, $data)
    {
        $curl = curl_init($url);
        $headers["Content-Type"] = "application/json";

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->parseHeaders($headers));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, self::DEFAULT_CONNECT_TIMEOUT);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::DEFAULT_TIMEOUT);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_CAINFO, CaBundle::getBundledCaBundlePath());

    }
}