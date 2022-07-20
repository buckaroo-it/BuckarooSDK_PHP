<?php

namespace Buckaroo\Handlers\Reply;

use Buckaroo\Config\Config;

class ReplyHandler
{
    private Config $config;
    private ReplyStrategy $strategy;

    private $data;

    private ?string $auth_header;
    private ?string $uri;

    private bool $isValid = false;

    public function __construct(Config $config, $data, $auth_header = null, $uri = null)
    {
        $this->config = $config;
        $this->data = $data;
        $this->auth_header = $auth_header;
        $this->uri = $uri;
    }

    public function validate()
    {
        $this->setStrategy();

        $this->isValid = $this->strategy->validate();

        return $this;
    }

    private function setStrategy()
    {
        $data = $this->data;

        if(is_string($data))
        {
            $data = json_decode($data, true);
        }

        if($this->contains('Transaction', $data))
        {
            $this->strategy = new Json($this->config, $data, $this->auth_header, $this->uri);

            return $this;
        }

        if($this->contains('brq_', $data))
        {
            $this->strategy = new HttpPost($this->config, $data);

            return $this;
        }

        throw new \Exception("No reply handler strategy applied.");
    }

    private function contains(string $needle, array $data): bool
    {
        foreach(array_keys($data) as $key)
        {
            if(str_contains($key, $needle))
            {
                return true;
            }
        }

        return false;
    }

    public function isValid()
    {
        return $this->isValid;
    }
}