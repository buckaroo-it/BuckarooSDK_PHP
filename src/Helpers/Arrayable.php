<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Helpers;

interface Arrayable
{
    /**
     * @return array
     */
    public function toArray();
}
