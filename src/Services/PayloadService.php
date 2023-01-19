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

namespace Buckaroo\Services;

use Buckaroo\Resources\Arrayable;

class PayloadService implements Arrayable
{
    /**
     * @var array
     */
    private array $payload;

    /**
     * @param $payload
     * @throws \Exception
     */
    public function __construct($payload)
    {
        $this->setPayload($payload);
    }

    /**
     * @param $payload
     * @return $this
     * @throws \Exception
     */
    protected function setPayload($payload)
    {
        if (is_array($payload))
        {
            $this->payload = $payload;

            return $this;
        }

        if (is_string($payload))
        {
            $this->payload = json_decode($payload, true);
        }

        if ($this->payload == null)
        {
            throw new \Exception("Invalid or empty payload. Array or json format required.");
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
