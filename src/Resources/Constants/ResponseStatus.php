<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

declare(strict_types=1);

namespace Buckaroo\Resources\Constants;

class ResponseStatus
{
    public const BUCKAROO_STATUSCODE_SUCCESS = '190';
    public const BUCKAROO_STATUSCODE_FAILED = '490';
    public const BUCKAROO_STATUSCODE_VALIDATION_FAILURE = '491';
    public const BUCKAROO_STATUSCODE_TECHNICAL_ERROR = '492';
    public const BUCKAROO_STATUSCODE_REJECTED = '690';
    public const BUCKAROO_STATUSCODE_WAITING_ON_USER_INPUT = '790';
    public const BUCKAROO_STATUSCODE_PENDING_PROCESSING = '791';
    public const BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER = '792';
    public const BUCKAROO_STATUSCODE_PAYMENT_ON_HOLD = '793';
    public const BUCKAROO_STATUSCODE_CANCELLED_BY_USER = '890';
    public const BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT = '891';

    public const BUCKAROO_AUTHORIZE_TYPE_CANCEL = 'I014';
    public const BUCKAROO_AUTHORIZE_TYPE_ACCEPT = 'I013';
    public const BUCKAROO_AUTHORIZE_TYPE_GROUP_TRANSACTION = 'I150';
}
