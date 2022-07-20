<?php

namespace Buckaroo\Handlers\Reply;

use Buckaroo\Config\Config;
use Buckaroo\Handlers\HMAC\Validator;

class Json implements ReplyStrategy
{
    private Config $config;
    private array $data;
    private string $auth_header;
    private string $uri;
    private string $method;

    public function __construct(Config $config, array $data, string $auth_header = '', string $uri = '', $method = 'POST')
    {
        $this->config = $config;
        $this->data = $data;
        $this->auth_header = $auth_header;
        $this->uri = $uri;
        $this->method = $method;
    }

    public function validate(): bool
    {
        $validator = new Validator($this->config);

        return $validator->validate($this->auth_header, $this->uri, $this->method, $this->data);
    }
}