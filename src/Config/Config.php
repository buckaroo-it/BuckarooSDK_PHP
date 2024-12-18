<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

declare(strict_types=1);

namespace Buckaroo\Config;

use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Logging\Loggable;
use Buckaroo\Handlers\Logging\Subject;

abstract class Config implements Loggable
{
    /**
     *
     */
    const LIVE_MODE = 'live';
    /**
     *
     */
    const TEST_MODE = 'test';

    /**
     * @var string
     */
    private string $websiteKey;
    /**
     * @var string
     */
    private string $secretKey;
    /**
     * @var string|mixed
     */
    private string $mode;
    /**
     * @var string|mixed
     */
    private string $currency;
    /**
     * @var string|mixed
     */
    private string $returnURL;
    /**
     * @var string|mixed
     */
    private string $returnURLCancel;
    /**
     * @var string|mixed
     */
    private string $pushURL;

    /**
     * @var string|mixed
     */
    private string $platformName;
    /**
     * @var string|mixed
     */
    private string $platformVersion;
    /**
     * @var string|mixed
     */
    private string $moduleSupplier;
    /**
     * @var string|mixed
     */
    private string $moduleName;
    /**
     * @var string|mixed
     */
    private string $moduleVersion;

    /**
     * @var string|mixed
     */
    private string $culture;
    /**
     * @var string|mixed
     */
    private string $channel;
    /**
     * @var Subject
     */
    protected Subject $logger;
    /**
     * @var int|null
     */
    private ?int $timeout;
    /**
     * @var int|null
     */
    private ?int $connectTimeout;

    /**
     * @param string $websiteKey
     * @param string $secretKey
     * @param string|null $mode
     * @param string|null $currency
     * @param string|null $returnURL
     * @param string|null $returnURLCancel
     * @param string|null $pushURL
     * @param string|null $platformName
     * @param string|null $platformVersion
     * @param string|null $moduleSupplier
     * @param string|null $moduleName
     * @param string|null $moduleVersion
     * @param string|null $culture
     * @param string|null $channel
     * @param Subject|null $logger
     * @param int|null $timeout
     * @param int|null $connectTimeout
     */
    public function __construct(
        string  $websiteKey,
        string  $secretKey,
        ?string $mode = null,
        ?string $currency = null,
        ?string $returnURL = null,
        ?string $returnURLCancel = null,
        ?string $pushURL = null,
        ?string $platformName = null,
        ?string $platformVersion = null,
        ?string $moduleSupplier = null,
        ?string $moduleName = null,
        ?string $moduleVersion = null,
        ?string $culture = null,
        ?string $channel = null,
        Subject $logger = null,
        ?int $timeout = null,
        ?int $connectTimeout = null
    ) {
        $this->websiteKey = $websiteKey;
        $this->secretKey = $secretKey;

        $this->mode = $_ENV['BPE_MODE'] ?? $mode ?? 'test';
        $this->currency = $_ENV['BPE_CURRENCY_CODE'] ?? $currency ?? 'EUR';
        $this->returnURL = $_ENV['BPE_RETURN_URL'] ?? $returnURL ?? '';
        $this->returnURLCancel = $_ENV['BPE_RETURN_URL_CANCEL'] ?? $returnURLCancel ?? '';
        $this->pushURL = $_ENV['BPE_PUSH_URL'] ?? $pushURL ?? '';
        $this->platformName = $_ENV['PlatformName'] ?? $platformName ?? 'Empty Platform Name';
        $this->platformVersion = $_ENV['PlatformVersion'] ?? $platformVersion ?? '1.0.0';
        $this->moduleSupplier = $_ENV['ModuleSupplier'] ?? $moduleSupplier ?? 'Empty Module Supplier';
        $this->moduleName = $_ENV['ModuleName'] ?? $moduleName ?? 'Empty Module name';
        $this->moduleVersion = $_ENV['ModuleVersion'] ?? $moduleVersion ?? '1.0.0';
        $this->culture = $_ENV['Culture'] ?? $culture ?? '';
        $this->channel = $_ENV['Channel'] ?? $channel ?? '';
        $this->timeout = $_ENV['BPE_HTTP_TIMEOUT'] ?? $timeout ?? null;
        $this->connectTimeout = $_ENV['BPE_HTTP_CONNECT_TIMEOUT'] ?? $connectTimeout ?? null;

        $this->setLogger($logger ?? new DefaultLogger());
    }

    /**
     * @return string
     */
    public function websiteKey(): string
    {
        return $this->websiteKey;
    }

    /**
     * @return string
     */
    public function secretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @return bool
     */
    public function isLiveMode(): bool
    {
        return $this->mode == self::LIVE_MODE;
    }

    /**
     * @param string|null $mode
     * @return string
     */
    public function mode(?string $mode = null): string
    {
        if ($mode && in_array($mode, [self::LIVE_MODE, self::TEST_MODE]))
        {
            $this->mode = $mode;
        }

        return $this->mode;
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function returnURL(): string
    {
        return $this->returnURL;
    }

    /**
     * @return string
     */
    public function returnURLCancel(): string
    {
        return $this->returnURLCancel;
    }

    /**
     * @return string
     */
    public function pushURL(): string
    {
        return $this->pushURL;
    }

    /**
     * @return string
     */
    public function platformName(): string
    {
        return $this->platformName;
    }

    /**
     * @return string
     */
    public function platformVersion(): string
    {
        return $this->platformVersion;
    }

    /**
     * @return string
     */
    public function moduleSupplier(): string
    {
        return $this->moduleSupplier;
    }

    /**
     * @return string
     */
    public function moduleName(): string
    {
        return $this->moduleName;
    }

    /**
     * @return string
     */
    public function moduleVersion(): string
    {
        return $this->moduleVersion;
    }

    /**
     * @return string
     */
    public function culture(): string
    {
        if (! empty($this->culture))
        {
            return $this->culture;
        }

        return 'en-GB';
    }

    /**
     * @return string
     */
    public function channel(): string
    {
        if (! empty($this->channel))
        {
            return $this->channel;
        }
        return 'Web';
    }

    /**
     * @param array $payload
     * @return $this
     */
    public function merge(array $payload)
    {
        $payload = $this->filterNonUpdatableKeys($payload);

        foreach ($payload as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * @param $payload
     * @return array
     */
    private function filterNonUpdatableKeys($payload)
    {
        $filter = ['websiteKey', 'secretKey'];

        return array_filter($payload, function ($k) use ($filter) {
            return ! in_array($k, $filter);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $properties
     * @return array
     */
    public function get(array $properties = [])
    {
        $values = [];

        foreach ($properties as $property)
        {
            if (method_exists($this, $property))
            {
                $values[$property] = $this->$property();
            }
        }

        return $values;
    }

    /**
     * @param Subject $logger
     * @return $this
     */
    public function setLogger(Subject $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return Subject|null
     */
    public function getLogger(): ?Subject
    {
        return $this->logger;
    }

    /**
     * @return int|null
     */
    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    /**
     * @return int|null
     */
    public function getConnectTimeout(): ?int
    {
        return $this->connectTimeout;
    }
}
