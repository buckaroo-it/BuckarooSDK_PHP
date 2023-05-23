<?php

namespace Buckaroo\Transaction\Request;

/**
 *
 */
class BatchRequest extends Request
{
    /**
     * @var array
     */
    protected array $transactions;

    /**
     * @param array $transactions
     */
    public function __construct(array $transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        $data = array_map(function ($transaction) {
            return $transaction->request()->toArray();
        }, $this->transactions);

        return json_encode($data);
    }
}
