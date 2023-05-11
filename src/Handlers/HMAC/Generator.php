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
 */

namespace Buckaroo\Handlers\HMAC;

use Buckaroo\Config\Config;
use Ramsey\Uuid\Uuid;

class Generator extends Hmac
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var string
     */
    protected ?string $base64Data;
    /**
     * @var string|mixed
     */
    protected string $method;
    /**
     * @var string
     */
    protected string $uri;
    /**
     * @var string
     */
    protected string $nonce;
    /**
     * @var string
     */
    protected string $time;
    /**
     * @var string
     */
    protected string $hash;

    /**
     * @param Config $config
     * @param $data
     * @param $uri
     * @param $method
     */
    public function __construct(Config $config, $data, $uri, $method = 'POST')
    {
        $this->config = $config;
        $this->method = $method;

        $this->base64Data($data);
        $this->uri($uri);
        $this->nonce(Uuid::uuid4());
        $this->time(time());
    }

    /**
     * @return string
     */
    public function generate()
    {
        $hashString = $this->config->websiteKey() .
            $this->method . $this->uri .
            $this->time . $this->nonce .
            $this->base64Data;

        $hash = hash_hmac('sha256', $hashString, $this->config->secretKey(), true);
        $hmac = base64_encode($hash);

        return implode(':', [
            $this->config->websiteKey(),
            $hmac,
            $this->nonce,
            $this->time,
        ]);
    }
}
