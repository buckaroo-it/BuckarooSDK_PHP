<?php

declare(strict_types=1);

namespace Buckaroo\Helpers;

interface Arrayable
{
    /**
     * @return array
     */
    public function toArray();
}
