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

namespace Buckaroo\Transaction\Response;

use ArrayAccess;
use Buckaroo\Resources\Arrayable;
use Exception;

class Response implements ArrayAccess, Arrayable
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param $data
     */
    public function __construct($response, $data)
    {
        $this->httpResponse = $response;
        $this->data = $data;
    }

    /** Implement ArrayAccess */
    public function offsetSet($offset, $value): void
    {
        throw new Exception("Can't set a value of a Response");
    }

    /** Implement ArrayAccess */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /** Implement ArrayAccess */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /** Implement ArrayAccess */
    public function offsetGet($offset): mixed
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Redirect all method calls prefixed with 'get'
     * to check if a param exists with that name
     * Return the param if it does
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $prefix = substr($method, 0, 3);
        $param = substr($method, 3);
        // $arg    = isset($args[0]) ? $args[0] : null;

        if ($prefix === 'get')
        {
            return $this->offsetGet($param);
        }

        throw new Exception("Call to undefined method " . __CLASS__ . '::' . $method);
    }

    /**
     * @return mixed
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /** Implement Arrayable */
    public function toArray(): array
    {
        return $this->data;
    }
}
