<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Helpers\Constants;

class PaymentStatus
{
    public const PARTIALLY_INVOICED                             = 9;
    public const COMPLETELY_INVOICED                            = 10;
    public const PARTIALLY_PAID                                 = 11;
    public const COMPLETELY_PAID                                = 12;
    public const FIRST_REMINDER                                 = 13;
    public const SECOND_REMINDER                                = 14;
    public const THIRD_REMINDER                                 = 15;
    public const ENCASHMENT                                     = 16;
    public const OPEN                                           = 17;
    public const RESERVED                                       = 18;
    public const DELAYED                                        = 19;
    public const RE_CREDITING                                   = 20;
    public const REVIEW_NECESSARY                               = 21;
    public const NO_CREDIT_APPROVED                             = 30;
    public const THE_CREDIT_HAS_BEEN_ACCEPTED                   = 32;
    public const THE_PAYMENT_HAS_BEEN_ORDERED_BY_HANSEATIC_BANK = 33;
    public const A_TIME_EXTENSION_HAS_BEEN_REGISTERED           = 34;
    public const THE_PROCESS_HAS_BEEN_CANCELLED                 = 35;

    public const PAID      = 12;
    public const REFUNDED  = 20;
    public const CANCELLED = 35;
}
