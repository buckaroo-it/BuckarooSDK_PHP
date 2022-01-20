<?php

declare(strict_types=1);

namespace Buckaroo;

use Buckaroo\Exceptions\SdkException;
use Buckaroo\Helpers\Validate;
use Psr\Log\LoggerInterface;

class Config
{
    protected array $data = [];

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function setWebsiteKey(string $websiteKey): void
    {
        if (!Validate::isWebsiteKey($websiteKey)) {
            throw new SdkException($this->logger, __METHOD__, "Invalid Website Key: '{$websiteKey}'. ");
        }

        $this->setValue('websiteKey', $websiteKey);
    }

    public function getWebsiteKey(): ?string
    {
        return $this->getValue('websiteKey');
    }

    public function setSecretKey(string $secretKey): void
    {
        if (!Validate::isSecretKey($secretKey)) {
            throw new SdkException($this->logger, __METHOD__, "Invalid Secret Key: '{$secretKey}'. ");
        }
        $this->setValue('secretKey', $secretKey);
    }

    public function getSecretKey(): ?string
    {
        return $this->getValue('secretKey');
    }

    public function setMode(string $mode): void
    {
        if (!Validate::isMode($mode)) {
            throw new SdkException($this->logger, __METHOD__, "Invalid Mode: '{$mode}'. ");
        }
        $this->setValue('mode', $mode);
    }

    public function getMode(): ?string
    {
        return $this->getValue('mode');
    }

    public function getValue($key): ?string
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function setValue($key, $value): void
    {
        $this->data[$key] = $value;
    }
}
