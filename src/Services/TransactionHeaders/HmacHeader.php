<?php

declare(strict_types=1);

namespace Buckaroo\Services\TransactionHeaders;

use Buckaroo\Config\Config;
use Buckaroo\Handlers\HMAC\Generator;

/**
 * Class to create the security header for Buckaroo
 * https://dev.buckaroo.nl/Apis/Description/json
 */
class HmacHeader extends TransactionHeader
{
    public function __construct(TransactionHeader $transactionHeader, Config $config, string $requestUri, string $content, string $httpMethod, string $nonce = '', int $timeStamp = 0)
    {
        $this->hmacGenerator = new Generator($config, $content, $requestUri, $httpMethod);

        parent::__construct($transactionHeader);
    }

    public function getHeaders(): array {
        $headers = $this->transactionHeader->getHeaders();

        $headers[] = "Authorization: hmac " . $this->hmacGenerator->generate();

        return $headers;
    }
}
