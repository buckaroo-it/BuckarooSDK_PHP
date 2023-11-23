<?php

namespace Buckaroo\Transaction\Request\HttpClient;

use Buckaroo\Handlers\Logging\Subject;
use Composer\InstalledVersions;

class HttpClientFactory
{
    public static function createClient(Subject $logger)
    {
        // Detect the installed GuzzleHttp version
        $versionString  = InstalledVersions::getVersion('guzzlehttp/guzzle');
        // Extract the major version number
        $majorVersion = (int) explode('.', $versionString)[0];

        // Instantiate the appropriate client based on the major version
        if ($majorVersion === 5) {
            return new GuzzleHttpClientV5($logger);
        }
        return new GuzzleHttpClientV7($logger);
    }
}
