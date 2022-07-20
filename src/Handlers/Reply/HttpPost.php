<?php

namespace Buckaroo\Handlers\Reply;

use Buckaroo\Config\Config;

class HttpPost implements ReplyStrategy
{
    private Config $config;
    private array $data;

    public function __construct(Config $config, array $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    public function validate(): bool
    {
        //Remove brq_signature from the equation
        $data = array_filter($this->data, function($key){
            return $key != 'brq_signature';
        }, ARRAY_FILTER_USE_KEY);

        //Combine the array keys with value
        $data = array_map(function($value, $key){
            return $key . '=' . $value;
        }, $data, array_keys($data));

        $dataString = implode('',  $data) . trim($this->config->secretKey());

        return hash_equals(sha1($dataString), trim($this->data['brq_signature'] ?? null));
    }
}