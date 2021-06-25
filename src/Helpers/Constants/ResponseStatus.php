<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Helpers\Constants;

class ResponseStatus
{
    public const BUCKAROO_STATUSCODE_SUCCESS               = '190';
    public const BUCKAROO_STATUSCODE_FAILED                = '490';
    public const BUCKAROO_STATUSCODE_VALIDATION_FAILURE    = '491';
    public const BUCKAROO_STATUSCODE_TECHNICAL_ERROR       = '492';
    public const BUCKAROO_STATUSCODE_REJECTED              = '690';
    public const BUCKAROO_STATUSCODE_WAITING_ON_USER_INPUT = '790';
    public const BUCKAROO_STATUSCODE_PENDING_PROCESSING    = '791';
    public const BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER   = '792';
    public const BUCKAROO_STATUSCODE_PAYMENT_ON_HOLD       = '793';
    public const BUCKAROO_STATUSCODE_CANCELLED_BY_USER     = '890';
    public const BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT = '891';

    public const BUCKAROO_AUTHORIZE_TYPE_CANCEL            = 'I014';
    public const BUCKAROO_AUTHORIZE_TYPE_ACCEPT            = 'I013';
    public const BUCKAROO_AUTHORIZE_TYPE_GROUP_TRANSACTION = 'I150';
}
