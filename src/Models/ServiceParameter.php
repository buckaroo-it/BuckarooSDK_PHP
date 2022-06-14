<?php

namespace Buckaroo\Models;

class ServiceParameter extends Model
{
    protected array $groupData = [];

    public function getGroupType(string $key): ?string
    {
        return $this->groupData[$key]['groupType'] ?? null;
    }

    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        return $this->groupData[$key]['groupKey'] ?? null;
    }
}