<?php

declare(strict_types=1);

namespace Buckaroo\Transaction;

class Config
{
    private $websiteKey;
    private $secretKey;
    private $mode;

    const LIVE_MODE = 'live';
    const TEST_MODE = 'test';

    public function __construct(string $websiteKey, string $secretKey, string $mode = null)
    {
        $this->websiteKey = $websiteKey;
        $this->secretKey = $secretKey;
        $this->mode = ($mode? $mode : $_ENV['BPE_MODE']);
    }

    public function websiteKey(): string {
        return $this->websiteKey;
    }

    public function secretKey(): string {
        return $this->secretKey;
    }

    public function mode(): string {
        return $this->mode;
    }

    public function isLiveMode(): bool
    {
        return $this->mode == self::LIVE_MODE;
    }
}
