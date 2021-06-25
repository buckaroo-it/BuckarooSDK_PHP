<?php declare (strict_types = 1);

namespace Buckaroo\SDK\Helpers\Constants;

class PaymentStatus
{
    const PARTIALLY_INVOICED                             = 9;
    const COMPLETELY_INVOICED                            = 10;
    const PARTIALLY_PAID                                 = 11;
    const COMPLETELY_PAID                                = 12;
    const FIRST_REMINDER                                 = 13;
    const SECOND_REMINDER                                = 14;
    const THIRD_REMINDER                                 = 15;
    const ENCASHMENT                                     = 16;
    const OPEN                                           = 17;
    const RESERVED                                       = 18;
    const DELAYED                                        = 19;
    const RE_CREDITING                                   = 20;
    const REVIEW_NECESSARY                               = 21;
    const NO_CREDIT_APPROVED                             = 30;
    const THE_CREDIT_HAS_BEEN_ACCEPTED                   = 32;
    const THE_PAYMENT_HAS_BEEN_ORDERED_BY_HANSEATIC_BANK = 33;
    const A_TIME_EXTENSION_HAS_BEEN_REGISTERED           = 34;
    const THE_PROCESS_HAS_BEEN_CANCELLED                 = 35;

    const PAID      = 12;
    const REFUNDED  = 20;
    const CANCELLED = 35;
}
