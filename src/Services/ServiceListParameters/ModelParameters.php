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

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\Model;
use Buckaroo\Models\ServiceList;
use Buckaroo\Models\ServiceParameter;

class ModelParameters extends ServiceListParameter
{
    /**
     * @var Model
     */
    protected Model $model;
    /**
     * @var string|null
     */
    protected ?string $groupType;
    /**
     * @var int|null
     */
    protected ?int $groupKey;

    /**
     * @param ServiceListParameter $serviceListParameter
     * @param Model $model
     * @param string|null $groupType
     * @param int|null $groupKey
     */
    public function __construct(
        ServiceListParameter $serviceListParameter,
        Model $model,
        ?string $groupType = '',
        ?int $groupKey = null
    ) {
        $this->model = $model;
        $this->groupType = $groupType;
        $this->groupKey = $groupKey;

        parent::__construct($serviceListParameter);
    }

    /**
     * @return ServiceList
     */
    public function data(): ServiceList
    {
        foreach ($this->model->toArray() as $key => $value)
        {
            if (! is_array($value))
            {
                $this->appendParameter(
                    $this->groupKey($key),
                    $this->groupType($key),
                    $this->model->serviceParameterKeyOf($key),
                    $value
                );
            }
        }

        return $this->serviceList;
    }

    /**
     * @param $key
     * @return int|null
     */
    private function groupKey($key)
    {
        if ($this->model instanceof ServiceParameter && ! $this->groupKey)
        {
            return $this->model->getGroupKey($key);
        }

        return $this->groupKey;
    }

    /**
     * @param $key
     * @return string|null
     */
    private function groupType($key)
    {
        if ($this->model instanceof ServiceParameter && ! $this->groupType)
        {
            return $this->model->getGroupType($key);
        }

        return $this->groupType;
    }
}
