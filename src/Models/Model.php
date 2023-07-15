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

namespace Buckaroo\Models;

use Buckaroo\Resources\Arrayable;

abstract class Model implements Arrayable
{
    /**
     * @param array|null $values
     */
    public function __construct(?array $values = null)
    {
        $this->setProperties($values);
    }

    /**
     * @param $property
     * @return null
     */
    public function __get($property)
    {
        if (property_exists($this, $property) && isset($this->$property))
        {
            return $this->$property;
        }

        return null;
    }

    /**
     * @param $property
     * @param $value
     * @return $this
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property))
        {
            $this->$property = $value;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getObjectVars()
    {
        return get_object_vars($this);
    }

    /**
     * @param array|null $data
     * @return $this
     */
    public function setProperties(?array $data)
    {
        if ($data)
        {
            foreach ($data ?? [] as $property => $value)
            {
                $this->$property = $value;
            }
        }

        return $this;
    }

    /**
     * @param $propertyName
     * @return string
     */
    public function serviceParameterKeyOf($propertyName)
    {
        return ucfirst($propertyName);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->recursiveToArray(get_object_vars($this));
    }

    /**
     * @param array $array
     * @return array
     */
    private function recursiveToArray(array $array) : array
    {
        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $array[$key] = $this->recursiveToArray($value);
            }

            if (is_a($value, Arrayable::class))
            {
                $array[$key] = $value->toArray();
            }
        }

        return $array;
    }
}
