<?php

namespace Buckaroo\PaymentMethods\Traits;

trait CountableGroupKey
{
    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        if($this->countable($key, $keyCount))
        {
            return intval($keyCount) + 1;
        }

        return $this->groupData[$key]['groupKey'] ?? null;
    }

    private function countable(string $key, ?int $keyCount = 0)
    {
        return isset($this->countableProperties) && in_array($key, $this->countableProperties) && is_numeric($keyCount);
    }
}