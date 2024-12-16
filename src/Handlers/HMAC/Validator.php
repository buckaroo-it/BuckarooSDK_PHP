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
use Buckaroo\Exceptions\BuckarooException;

class Validator extends Hmac
{
    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var string
     */
    protected string $base64Data;
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
    protected string $hash = '';

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $header
     * @param string $uri
     * @param string $method
     * @param $data
     * @return bool
     */
    public function validate(string $header, string $uri, string $method, $data)
    {
        $header = explode(':', $header);

        $providedHash = $header[1];

        $this->uri($uri);
        $this->nonce($header[2]);
        $this->time($header[3]);

        $this->base64Data($data);

        $hmac = $this->config->websiteKey() . $method . $this->uri . $this->time . $this->nonce . $this->base64Data;

        $this->hash = base64_encode(hash_hmac('sha256', $hmac, $this->config->secretKey(), true));

        return $providedHash == $this->hash;
    }

    /**
     * @param string $header
     * @param string $uri
     * @param string $method
     * @param $data
     * @return bool
     * @throws BuckarooException
     */
    public function validateOrFail(string $header, string $uri, string $method, $data)
    {
        if ($this->validate($header, $uri, $method, $data))
        {
            return true;
        }

        throw new BuckarooException($this->config->getLogger(), "HMAC validation failed.");
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}
