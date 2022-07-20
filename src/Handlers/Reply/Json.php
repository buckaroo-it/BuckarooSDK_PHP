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

    public function __construct(Config $config, array $data, string $auth_header = '', string $uri = '')
    {
        $this->config = $config;
        $this->data = $data;
        $this->auth_header = $auth_header;
        $this->uri = $uri;
    }

    public function validate(): bool
    {
        $validator = new Validator($this->config);

        return $validator->validate($this->auth_header, $this->uri, 'POST', $this->data);
    }
}