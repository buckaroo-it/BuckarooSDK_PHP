<?php

declare(strict_types=1);

namespace Buckaroo\Payload;

use ArrayAccess;
use Buckaroo\Helpers\Arrayable;
use Exception;

class Response implements ArrayAccess, Arrayable
{
    protected $data = [];

    public function __construct($data)
    {
        $this->data     = $data;
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
    public function offsetGet($offset)
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
        $param  = substr($method, 3);
        // $arg    = isset($args[0]) ? $args[0] : null;

        if ($prefix === 'get') {
            return $this->offsetGet($param);
        }

        throw new Exception("Call to undefined method " . __CLASS__ . '::' . $method);
    }

    /** Implement Arrayable */
    public function toArray(): array
    {
        return $this->data;
    }
}
