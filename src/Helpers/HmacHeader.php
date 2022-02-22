<?php

declare(strict_types=1);

namespace Buckaroo\Helpers;

use Buckaroo\Config;

/**
 * Class to create the security header for Buckaroo
 * https://dev.buckaroo.nl/Apis/Description/json
 */
class HmacHeader
{
    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getHeader(
        string $requestUri,
        string $content,
        string $httpMethod,
        string $nonce = '',
        int $timeStamp = 0
    ): string {
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
            $this->config->getWebsiteKey(),
            $this->getHash($requestUri, $httpMethod, $encodedContent, $nonce, $timeStamp),
            $nonce,
            $timeStamp,
        ]);

        return $hmac;
    }

    protected function getNonce(): string
    {
        $length = 16;
        return Base::stringRandom($length);
    }

    protected function getTimeStamp(): int
    {
        return time();
    }

    protected function getHash(
        string $requestUri,
        string $httpMethod,
        string $encodedContent,
        string $nonce,
        int $timeStamp
    ): string {
        $rawData = $this->config->getWebsiteKey() . $httpMethod . $requestUri . $timeStamp . $nonce . $encodedContent;
        $hash = hash_hmac('sha256', $rawData, $this->config->getSecretKey(), true);
        $base64 = base64_encode($hash);

        return $base64;
    }

    protected function getEncodedContent(string $content = ''): string
    {
        if ($content) {
            $md5    = md5($content, true);
            $base64 = base64_encode($md5);
            return $base64;
        }

        return $content;
    }

    protected function escapeRequestUri(string $requestUri): string
    {
        $requestUri = Base::stringRemoveStart($requestUri, 'http://');
        $requestUri = Base::stringRemoveStart($requestUri, 'https://');

        return strtolower(rawurlencode($requestUri));
    }
}
