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
    const LIVE_MODE = 'live';
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
     * @var Subject
     */
    protected Subject $logger;

    /**
     * @param string $websiteKey
     * @param string $secretKey
     * @param string|null $mode
     * @param string|null $currency
     * @param string|null $returnURL
     * @param string|null $returnURLCancel
     * @param string|null $pushURL
     * @param Subject|null $logger
     */
    public function __construct(
        string $websiteKey,
        string $secretKey,
        ?string $mode = null,
        ?string $currency = null,
        ?string $returnURL = null,
        ?string $returnURLCancel = null,
        ?string $pushURL = null,
        Subject $logger = null
    ) {
        $this->websiteKey = $websiteKey;
        $this->secretKey = $secretKey;

        $this->mode = $_ENV['BPE_MODE'] ?? $mode ?? 'test';
        $this->currency = $_ENV['BPE_CURRENCY_CODE'] ?? $currency ?? 'EUR';
        $this->returnURL = $_ENV['BPE_RETURN_URL'] ?? $returnURL ?? '';
        $this->returnURLCancel = $_ENV['BPE_RETURN_URL_CANCEL'] ?? $returnURLCancel ?? '';
        $this->pushURL = $_ENV['BPE_PUSH_URL'] ?? $pushURL ?? '';

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

        return array_filter($payload, function ($k) use ($filter)
        {
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
}
