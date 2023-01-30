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

namespace Buckaroo\PaymentMethods\Traits;

trait CountableGroupKey
{
    /**
     * @param string $key
     * @param int|null $keyCount
     * @return int|null
     */
    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        if ($this->countable($key, $keyCount))
        {
            return intval($keyCount) + 1;
        }

        return $this->groupData[$key]['groupKey'] ?? null;
    }

    /**
     * @param string $key
     * @param int|null $keyCount
     * @return bool
     */
    private function countable(string $key, ?int $keyCount = 0)
    {
        return isset($this->countableProperties) && in_array($key, $this->countableProperties) && is_numeric($keyCount);
    }
}
