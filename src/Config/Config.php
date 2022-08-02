<?php

declare(strict_types=1);

namespace Buckaroo\Config;

use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Logging\Loggable;
use Buckaroo\Handlers\Logging\Subject;

abstract class Config implements Loggable
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

    protected Subject $logger;

    public function __construct(string $websiteKey, string $secretKey, ?string $mode = null, ?string $currency = null, ?string $returnURL = null, ?string $returnURLCancel = null, ?string $pushURL = null, Subject $logger = null)
    {
        $this->websiteKey = $websiteKey;
        $this->secretKey = $secretKey;

        $this->mode = $_ENV['BPE_MODE'] ?? $mode ?? 'test';
        $this->currency = $_ENV['BPE_CURRENCY_CODE'] ?? $currency ?? 'EUR';
        $this->returnURL = $_ENV['BPE_RETURN_URL'] ?? $returnURL ?? '';
        $this->returnURLCancel = $_ENV['BPE_RETURN_URL_CANCEL'] ?? $returnURLCancel ?? '';
        $this->pushURL = $_ENV['BPE_PUSH_URL'] ?? $pushURL ?? '';

        $this->setLogger($logger ?? new DefaultLogger());
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

    public function currency(): string
    {
        return $this->currency;
    }

    public function returnURL(): string
    {
        return $this->returnURL;
    }

    public function returnURLCancel(): string
    {
        return $this->returnURLCancel;
    }

    public function pushURL(): string
    {
        return $this->pushURL;
    }

    public function merge(array $payload)
    {
        $payload = $this->filterNonUpdatableKeys($payload);

        foreach($payload as $key => $value)
        {
            if(property_exists($this, $key))
            {
                $this->$key = $value;
            }
        }

        return $this;
    }

    private function filterNonUpdatableKeys($payload)
    {
        $filter = array('websiteKey', 'secretKey');

        return array_filter($payload, function($k) use ($filter){
            return !in_array($k, $filter);
        }, ARRAY_FILTER_USE_KEY);
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

    public function setLogger(Subject $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function getLogger(): ?Subject
    {
        return $this->logger;
    }
}
