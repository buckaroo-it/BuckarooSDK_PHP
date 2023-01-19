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

namespace Buckaroo\Models\Adapters;

use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceParameter;

abstract class ServiceParametersKeysAdapter extends ServiceParameter
{
    /**
     * @var Model
     */
    private Model $model;
    /**
     * @var array
     */
    protected array $hidden = [];
    /**
     * @var array
     */
    protected array $keys = [];

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $property
     * @return null
     */
    public function __get($property)
    {
        if (property_exists($this->model, $property))
        {
            return $this->model->$property;
        }

        return null;
    }

    /**
     * @param $propertyName
     * @return string
     */
    public function serviceParameterKeyOf($propertyName): string
    {
        return (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->model->toArray();
    }

    /**
     * @return array
     */
    public function getObjectVars(): array
    {
        return $this->model->getObjectVars();
    }
}
