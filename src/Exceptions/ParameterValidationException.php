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

namespace Buckaroo\Exceptions;

/**
 * The input passed to the SDK is invalid or incomplete, so no request was sent.
 */
class ParameterValidationException extends BuckarooException
{
    protected ?string $parameterName = null;

    public static function forParameter(string $parameterName, string $message): self
    {
        $exception = new static($message);
        $exception->parameterName = $parameterName;

        return $exception;
    }

    /**
     * The name of the invalid field, or null when the SDK does not know it.
     */
    public function getParameterName(): ?string
    {
        return $this->parameterName;
    }
}
