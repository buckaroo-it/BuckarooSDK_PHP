<?php

namespace Buckaroo\Handlers\HMAC;

use Buckaroo\Config\Config;

class Validator extends Hmac
{
    protected Config $config;

    protected string $base64Data;
    protected string $uri;
    protected string $nonce;
    protected string $time;
    protected string $hash;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

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

    public function validateOrFail(string $header, string $uri, string $method, $data)
    {
        if($this->validate($header, $uri, $method, $data))
        {
            return true;
        }

        throw new \Exception("HMAC validation failed.");
    }
}