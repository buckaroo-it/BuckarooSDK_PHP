<?php

namespace Buckaroo\Transaction\Request\HttpClient;

use Buckaroo\Exceptions\BuckarooException;
use Composer\CaBundle\CaBundle;

class HttpCurl implements HttpClientInterface
{

    const DEFAULT_TIMEOUT = 10;
    const DEFAULT_CONNECT_TIMEOUT = 2;
    const HTTP_NO_CONTENT = 204;

    public function call(string $url, array $headers, string $method, string $data = null)
    {
        try {
            return $this->attemptCall($url, $headers, $method, $data);
        } catch (BuckarooException $e) {
            // Nothing
        }
    }

    private function attemptCall(string $url, array $headers, string $method, string $data = null)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, self::DEFAULT_CONNECT_TIMEOUT);
        curl_setopt($curl, CURLOPT_TIMEOUT, self::DEFAULT_TIMEOUT);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_CAINFO, CaBundle::getBundledCaBundlePath());

        switch ($method) {
            case HttpClient::HTTP_POST:
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS,  $data);

                break;
            case HttpClient::HTTP_GET:
                break;
            case HttpClient::HTTP_PATCH:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                break;
            case HttpClient::HTTP_DELETE:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_POSTFIELDS,  $data);

                break;
            default:
                throw new \InvalidArgumentException("Invalid http method: ". $method);
        }

        $startTime = microtime(true);
        $response = curl_exec($curl);
        $endTime = microtime(true);

        dd($response);
    }
}