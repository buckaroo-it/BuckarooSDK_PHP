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

namespace Buckaroo\Handlers\Reply;

use Buckaroo\Config\Config;

class ReplyHandler
{
    /**
     * @var Config
     */
    private Config $config;
    /**
     * @var ReplyStrategy
     */
    private ReplyStrategy $strategy;

    /**
     * @var
     */
    private $data;

    /**
     * @var string|mixed|null
     */
    private ?string $auth_header;
    /**
     * @var string|mixed|null
     */
    private ?string $uri;

    /**
     * @var bool
     */
    private bool $isValid = false;

    /**
     * @param Config $config
     * @param $data
     * @param $auth_header
     * @param $uri
     */
    public function __construct(Config $config, $data, $auth_header = null, $uri = null)
    {
        $this->config = $config;
        $this->data = $data;
        $this->auth_header = $auth_header;
        $this->uri = $uri;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function validate()
    {
        $this->setStrategy();

        $this->isValid = $this->strategy->validate();

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function setStrategy()
    {
        $data = $this->data;

        if (is_string($data))
        {
            $data = json_decode($data, true);
        }

        if (($this->contains('Transaction', $data) || $this->contains('DataRequest', $data)) &&
            $this->auth_header &&
            $this->uri
        )
        {
            $this->strategy = new Json($this->config, $data, $this->auth_header, $this->uri);

            return $this;
        }

        if ($this->contains('brq_', $data) || $this->contains('BRQ_', $data))
        {
            $this->strategy = new HttpPost($this->config, $data);

            return $this;
        }

        throw new \Exception("No reply handler strategy applied.");
    }

    /**
     * @param string $needle
     * @param array $data
     * @return bool
     */
    private function contains(string $needle, array $data, bool $strict = false): bool
    {
        foreach (array_keys($data) as $key)
        {
            if ($strict && $key == $needle)
            {
                return true;
            }

            if (! $strict && str_contains($key, $needle))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * @return mixed
     */
    public function data($key = null)
    {
        if ($key)
        {
            return $this->data[$key] ?? $this->data[strtolower($key)] ?? $this->data[strtoupper($key)] ?? null;
        }

        return $this->data;
    }
}
