<?php

/**
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

namespace Buckaroo\Exceptions;

use Psr\Log\LoggerInterface;

class SdkException extends \Exception
{
    protected LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger,
        $breakpoint,
        $message = "",
        $code = 0,
        \Throwable $previous = null
    ) {
        $this->logger = $logger;

        $message = 'Buckaroo SDK error: ' . $message;
        $this->logger->error($breakpoint, [$message]);

        parent::__construct($message, $code, $previous);
    }
}
