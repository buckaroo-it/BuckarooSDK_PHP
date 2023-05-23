<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Request\BatchRequest;

/**
 *
 */
class BatchTransactions
{
    /**
     * @var Client
     */
    protected Client $client;
    /**
     * @var BatchRequest
     */
    protected BatchRequest $batch_transactions;

    /**
     * @param Client $client
     * @param array $transactions
     */
    public function __construct(Client $client, array $transactions)
    {
        $this->client = $client;

        $this->batch_transactions = new BatchRequest($transactions);
    }

    /**
     * @return mixed
     * @throws \Buckaroo\Exceptions\BuckarooException
     */
    public function execute()
    {
        return $this->client->dataBatchRequest($this->batch_transactions);
    }
}
