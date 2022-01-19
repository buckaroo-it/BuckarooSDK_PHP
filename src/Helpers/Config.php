<?php

namespace Buckaroo\Helpers;

class Config
{
    /**
     * @var array
     */
    protected $data = array();

    public function __construct($params = null)
    {
        if ($params && is_array($params)) {
            foreach ($params as $key => $param) {
                $this->data[$key] = $param;
            }
        }
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }
}
