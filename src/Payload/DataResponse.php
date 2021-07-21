<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Payload;

use Buckaroo\SDK\Payload\TransactionResponse;

/**
 * DataResponse inherits from TransactionResponse
 * All differences between the two are fixed here
 */
class DataResponse extends TransactionResponse
{
    /**
     * Set an additional parameter
     * Structure is AdditionalParameters -> List
     *
     * @return array [ name => value ]
     */
    public function getAdditionalParameters()
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
