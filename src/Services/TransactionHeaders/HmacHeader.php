<?php
/*
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
 * Class to create the security header for Buckaroo
 * https://dev.buckaroo.nl/Apis/Description/json
 */

declare(strict_types=1);

namespace Buckaroo\Services\TransactionHeaders;

use Buckaroo\Config\Config;
use Buckaroo\Handlers\HMAC\Generator;

class HmacHeader extends TransactionHeader
{
    public function __construct(
        TransactionHeader $transactionHeader,
        Config $config,
        string $requestUri,
        string $content,
        string $httpMethod,
        string $nonce = '',
        int $timeStamp = 0
    ) {
        $this->hmacGenerator = new Generator($config, $content, $requestUri, $httpMethod);

        parent::__construct($transactionHeader);
    }

    public function getHeaders(): array
    {
        $headers = $this->transactionHeader->getHeaders();

        $headers[] = "Authorization: hmac " . $this->hmacGenerator->generate();

        return $headers;
    }
}
