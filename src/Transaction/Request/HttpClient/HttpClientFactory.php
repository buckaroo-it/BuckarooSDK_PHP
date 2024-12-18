<?php

namespace Buckaroo\Transaction\Request\HttpClient;

use Buckaroo\Config\Config;
use Composer\InstalledVersions;

class HttpClientFactory
{
    public static function createClient(Config $config)
    {
        // Detect the installed GuzzleHttp version
        $versionString  = InstalledVersions::getVersion('guzzlehttp/guzzle');
        // Extract the major version number
        $majorVersion = (int) explode('.', $versionString)[0];

        // Instantiate the appropriate client based on the major version
        if ($majorVersion === 5) {
            return new GuzzleHttpClientV5($config);
        }
        return new GuzzleHttpClientV7($config);
    }
}
