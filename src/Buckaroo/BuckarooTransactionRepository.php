<?php declare(strict_types=1);

namespace Buckaroo\SDK\Buckaroo;

use DateTime;
use Buckaroo\SDK\Buckaroo\Entity\Transaction\BuckarooTransactionEntity;

class BuckarooTransactionRepository
{
    /** @var EntityRepositoryInterface */
    private $cardRepository;

    public function __construct(EntityRepositoryInterface $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }
}
