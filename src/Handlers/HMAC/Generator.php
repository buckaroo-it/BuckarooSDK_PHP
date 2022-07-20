<?php

namespace Buckaroo\Handlers\HMAC;

use Buckaroo\Config\Config;

class Generator extends Hmac
{
    protected Config $config;

    protected string $base64Data;
    protected string $method;
    protected string $uri;
    protected string $nonce;
    protected string $time;
    protected string $hash;

    public function __construct(Config $config, $data, $uri, $method = 'POST')
    {
        $this->config = $config;
        $this->method = $method;

        $this->base64Data($data);
        $this->uri($uri);
        $this->nonce('nonce_' . rand(0000000, 9999999));
        $this->time(time());
    }

    public function generate()
    {
        $hashString = $this->config->websiteKey() . $this->method . $this->uri . $this->time . $this->nonce . $this->base64Data;
        $hash = hash_hmac('sha256', $hashString, $this->config->secretKey(), true);
        $hmac = base64_encode($hash);

        return implode(':', [
            $this->config->websiteKey(),
            $hmac,
            $this->nonce,
            $this->time
        ]);
    }
}