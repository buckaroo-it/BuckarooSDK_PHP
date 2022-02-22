<?php

declare(strict_types=1);

namespace Buckaroo\Payload;

/**
 * DataResponse inherits from TransactionResponse
 * All differences between the two are fixed here
 */
class DataResponse extends TransactionResponse
{
    public function getAdditionalParameters(): array
    {
        if (!empty($this->data['AdditionalParameters']['List'])) {
            $parameters = $this->data['AdditionalParameters']['List'];

            $params = [];

            foreach ($parameters as $parameter) {
                $params[$parameter['Name']] = $parameter['Value'];
            }

            return $params;
        }

        return [];
    }
}
