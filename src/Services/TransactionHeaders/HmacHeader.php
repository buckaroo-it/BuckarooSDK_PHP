<?php

declare(strict_types=1);

namespace Buckaroo\Services\TransactionHeaders;

use Buckaroo\Config\Config;
use Buckaroo\Helpers\Base;

/**
 * Class to create the security header for Buckaroo
 * https://dev.buckaroo.nl/Apis/Description/json
 */
class HmacHeader extends TransactionHeader
{
    public function __construct(TransactionHeader $transactionHeader, Config $config, string $requestUri, string $content, string $httpMethod, string $nonce = '', int $timeStamp = 0)
    {
        $this->config = $config;

        $this->nonce = ($nonce)? $nonce : str_random(16);
        $this->timeStamp = ($timeStamp)? $timeStamp : time();
        $this->encodedContent = base64_encode(md5($content, true));
        $this->requestUri = strtolower(rawurlencode(preg_replace("(^https?://)", "", $requestUri )));
        $this->httpMethod = strtoupper($httpMethod);

        $this->hash = $this->getHash();

        parent::__construct($transactionHeader);
    }

    public function getHeaders(): array {
        $headers = $this->transactionHeader->getHeaders();

        $headers[] = "Authorization: hmac " . implode(':', [
            $this->config->websiteKey(),
            $this->getHash(),
            $this->nonce,
            $this->timeStamp,
        ]);

        return $headers;
    }

    private function getHash(): string {
        $rawData = $this->config->websiteKey() . $this->httpMethod . $this->requestUri . $this->timeStamp . $this->nonce . $this->encodedContent;
        $hash = hash_hmac('sha256', $rawData, $this->config->secretKey(), true);

        return base64_encode($hash);
    }
}
