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

class ServiceParameter extends Model
{
    /**
     * @var array
     */
    protected array $groupData = [];

    /**
     * @param array|null $data
     * @return $this|ServiceParameter
     */
    public function setProperties(?array $data)
    {
        foreach ($data ?? [] as $property => $value)
        {
            if (method_exists($this, $property))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getGroupType(string $key): ?string
    {
        return $this->groupData[$key]['groupType'] ?? null;
    }

    /**
     * @param string $key
     * @param int|null $keyCount
     * @return int|null
     */
    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        return $this->groupData[$key]['groupKey'] ?? null;
    }
}
