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

use Buckaroo\Models\ServiceList;

abstract class ServiceListParameter
{
    /**
     * @var ServiceListParameter
     */
    protected ServiceListParameter $serviceListParameter;
    /**
     * @var ServiceList
     */
    protected ServiceList $serviceList;
    /**
     * @var array
     */
    protected array $data;

    /**
     * @param ServiceListParameter $serviceListParameter
     */
    public function __construct(ServiceListParameter $serviceListParameter)
    {
        $this->serviceListParameter = $serviceListParameter;
        $this->serviceList = $this->serviceListParameter->data();
    }

    /**
     * @return ServiceList
     */
    public function data(): ServiceList
    {
        return $this->serviceList;
    }

    /**
     * @param int|null $groupKey
     * @param string|null $groupType
     * @param string $name
     * @param $value
     * @return $this
     */
    protected function appendParameter(?int $groupKey, ?string $groupType, string $name, $value)
    {
        if (! is_null($value))
        {
            $this->serviceList->appendParameter([
                "Name" => $name,
                "Value" => $value,
                "GroupType" => (is_null($groupType))? "" : $groupType,
                "GroupID" => (is_null($groupKey))? "" : $groupKey,
            ]);
        }

        return $this;
    }
}
