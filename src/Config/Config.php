<?php

declare(strict_types=1);

namespace Buckaroo\Config;

abstract class Config
{
    const LIVE_MODE = 'live';
    const TEST_MODE = 'test';

    private string $websiteKey;
    private string $secretKey;
    private string $mode;
    private string $currency;
    private string $returnURL;
    private string $returnURLCancel;
    private string $pushURL;

    public function __construct(string $websiteKey, string $secretKey, string $mode = null)
    {
        $this->websiteKey = $websiteKey;
        $this->secretKey = $secretKey;
        $this->mode = ($mode? $mode : $_ENV['BPE_MODE']);

        $this->currency = $_ENV['BPE_EXAMPLE_CURRENCY_CODE'] ?? 'EUR';
        $this->returnURL = $_ENV['BPE_EXAMPLE_RETURN_URL'];
        $this->returnURLCancel = $_ENV['BPE_EXAMPLE_RETURN_URL'];
        $this->pushURL = $_ENV['BPE_EXAMPLE_RETURN_URL'];
    }

    public function websiteKey(): string {
        return $this->websiteKey;
    }

    public function secretKey(): string {
        return $this->secretKey;
    }

    public function isLiveMode(): bool
    {
        return $this->mode == self::LIVE_MODE;
    }

    public function mode(?string $mode = null): string
    {
        if($mode && in_array($mode, [self::LIVE_MODE, self::TEST_MODE]))
        {
            $this->mode = $mode;
        }

        return $this->mode;
    }

    public function currency(?string $currency = null): string
    {
        if($currency)
        {
            $this->currency = $currency;
        }

        return $this->currency;
    }

    public function returnURL(?string $returnURL = null): string
    {
        if($returnURL)
        {
            $this->returnURL = $returnURL;
        }

        return $this->returnURL;
    }

    public function returnURLCancel(?string $returnURLCancel = null): string
    {
        if($returnURLCancel)
        {
            $this->returnURLCancel = $returnURLCancel;
        }

        return $this->returnURLCancel;
    }

    public function pushURL(?string $pushURL = null): string
    {
        if($pushURL)
        {
            $this->pushURL = $pushURL;
        }

        return $this->pushURL;
    }

    public function merge(array $payload)
    {
        foreach($payload as $key => $value)
        {
            if(method_exists($this, $key))
            {
                $this->$key($value);
            }
        }

        return $this;
    }

    public function get(array $properties = [])
    {
        $values = array();

        foreach($properties as $property)
        {
            if(method_exists($this, $property))
            {
                $values[$property] = $this->$property();
            }
        }

        return $values;
    }
}