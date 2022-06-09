<?php

namespace Buckaroo\Handlers\Reply;

use Buckaroo\Config\Config;

class ReplyHandler
{
    private Config $config;
    private array $data;

    private bool $isValid = false;

    public function __construct(Config $config, array $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    public function validate()
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

        $this->isValid = hash_equals(sha1($dataString), trim($this->data['brq_signature'] ?? null));

        return $this;
    }

    public function isValid()
    {
        return $this->isValid;
    }
}