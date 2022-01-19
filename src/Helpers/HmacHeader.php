<?php

declare(strict_types=1);

namespace Buckaroo\Helpers;

use Buckaroo\Helpers\Config;
use Buckaroo\Helpers\Base;

/**
 * Class to create the security header for Buckaroo
 * https://dev.buckaroo.nl/Apis/Description/json
 */
class HmacHeader
{
    /**
     * @var Buckaroo\Helper\Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * getHeader
     * Get Hmac header for a request
     *
     * @param  [string] $requestUri Url to call
     * @param  [string] $content    Data to send
     * @param  [string] $httpMethod Should be GET or POST
     * @param  [string] $nonce      [optional] Nonce to be used
     * @param  [string] $timeStamp  [optional] TimeStamp to be used
     *
     * @return [string]             Hmac header
     */
    public function getHeader($requestUri, $content, $httpMethod, $nonce = '', $timeStamp = '')
    {
        if (empty($nonce)) {
            $nonce = $this->getNonce();
        }

        if (empty($timeStamp)) {
            $timeStamp = $this->getTimeStamp();
        }

        $encodedContent = $this->getEncodedContent($content);
        $httpMethod     = strtoupper($httpMethod);

        $requestUri = $this->escapeRequestUri($requestUri);

        $hmac = "Authorization: hmac " . implode(':', [
            $this->config->get('websiteKey'),
            $this->getHash($requestUri, $httpMethod, $encodedContent, $nonce, $timeStamp),
            $nonce,
            $timeStamp,
        ]);

        return $hmac;
    }

    protected function getNonce()
    {
        $length = 16;
        return Base::stringRandom($length);
    }

    protected function getTimeStamp()
    {
        return time();
    }

    protected function getHash($requestUri, $httpMethod, $encodedContent, $nonce, $timeStamp)
    {
        $rawData = $this->config->get('websiteKey') . $httpMethod . $requestUri . $timeStamp . $nonce . $encodedContent;

        $hash = hash_hmac('sha256', $rawData, $this->config->get('secretKey'), true);

        $base64 = base64_encode($hash);

        return $base64;
    }

    protected function getEncodedContent($content = '')
    {
        if ($content) {
            $md5    = md5($content, true);
            $base64 = base64_encode($md5);
            return $base64;
        }

        return $content;
    }

    protected function escapeRequestUri($requestUri)
    {
        $requestUri = Base::stringRemoveStart($requestUri, 'http://');
        $requestUri = Base::stringRemoveStart($requestUri, 'https://');

        return strtolower(rawurlencode($requestUri));
    }
}
