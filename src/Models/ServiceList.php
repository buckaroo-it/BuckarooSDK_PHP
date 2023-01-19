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

use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\ModelParameters;
use Buckaroo\Services\ServiceListParameters\ServiceListParameter;

class ServiceList extends Model
{
    /**
     * @var int
     */
    protected int $version;
    /**
     * @var string
     */
    protected string $action;
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var array
     */
    protected array $parameters = [];
    /**
     * @var ServiceListParameter|DefaultParameters
     */
    private ServiceListParameter $parameterService;

    /**
     * @param string $name
     * @param int $version
     * @param string $action
     * @param Model|null $model
     */
    public function __construct(string $name, int $version, string $action, ?Model $model = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->action = $action;

        $this->parameterService = new DefaultParameters($this);

        if ($model)
        {
            $this->decorateParameters($model);
            $this->parameterService->data();
        }

        parent::__construct();
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param $value
     * @param $key
     * @return $this
     */
    public function appendParameter($value, $key = null)
    {
        /* Check value pass multiple, iterate through it*/
        if (is_array($value) && is_array(current($value)))
        {
            foreach ($value as $singleValue)
            {
                $this->appendParameter($singleValue, $key);
            }

            return $this;
        }

        if ($key)
        {
            $this->parameters[$key] = $value;

            return $this;
        }

        $this->parameters[] = $value;

        return $this;
    }

    /**
     * @param Model $model
     * @param string|null $groupType
     * @param int|null $groupKey
     * @return $this
     */
    protected function decorateParameters(Model $model, ?string $groupType = null, ?int $groupKey = null)
    {
        $this->parameterService = new ModelParameters($this->parameterService, $model, $groupType, $groupKey);

        $this->iterateThroughObject($model, $model->getObjectVars());

        return $this;
    }

    /**
     * @param Model $model
     * @param array $array
     * @param string|null $keyName
     * @return $this
     */
    protected function iterateThroughObject(Model $model, array $array, ?string $keyName = null)
    {
        foreach ($array as $key => $value)
        {
            if ($model instanceof ServiceParameter && $value instanceof Model)
            {
                $this->decorateParameters(
                    $value,
                    $model->getGroupType($keyName ?? $key),
                    $model->getGroupKey($keyName ?? $key, is_int($key)? $key : null)
                );

                continue;
            }

            if (is_array($value) && count($value))
            {
                $this->iterateThroughObject($model, $value, $key);
            }
        }

        return $this;
    }
}
