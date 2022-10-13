<?php

namespace Buckaroo\Transaction\Request\HttpClient;

use Buckaroo\Handlers\Logging\Subject;

class HttpClient implements HttpClientInterface
{
    protected Subject $logger;

    const HTTP_GET = "GET";
    const HTTP_POST = "POST";
    const HTTP_DELETE = "DELETE";
    const HTTP_PATCH = "PATCH";

    public function __construct(Subject $logger)
    {
        $this->logger = $logger;

        $this->httpClient = $this->setHttpClient();
    }

    public function call(string $url, array $headers, string $method, string $data = null)
    {
        return $this->httpClient->call($url, $headers, $method, $data);
    }

    private function setHttpClient()
    {
        if ($this->guzzleIsDetected()) {
            return new HttpClientGuzzle;
        }

        return new HttpCurl;
    }

    /**
     * @return bool
     */
    private function guzzleIsDetected()
    {
        return interface_exists("\GuzzleHttp\ClientInterface");
    }
}