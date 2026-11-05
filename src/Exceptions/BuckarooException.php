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

namespace Buckaroo\Exceptions;

use Buckaroo\Handlers\Logging\Subject;
use Exception;
use Throwable;

/**
 * Base class for every exception the SDK throws. Catch a subclass to handle a specific cause.
 *
 * An exception means the request did not complete. A payment that Buckaroo declined is not
 * an exception: it is a TransactionResponse with a non-success status (isFailed(), ...).
 *
 * The constructor accepts the standard Exception signature, or the original signature with
 * a logger first: new BuckarooException(?Subject $logger, string $message, ...). The logger
 * form is kept for backwards compatibility and logs the message. It is deprecated.
 */
class BuckarooException extends Exception
{
    /**
     * @deprecated Only set by the logger-first constructor.
     */
    protected ?Subject $logger = null;

    protected ?int $httpStatus = null;

    protected ?string $httpBody = null;

    protected array $headers = [];

    /**
     * @param string|Subject|null $message  The message, or a logger (deprecated form)
     * @param mixed ...$arguments           int $code, ?Throwable $previous; in the deprecated
     *                                      form: string $message, int $code, ?Throwable $previous
     */
    public function __construct($message = '', ...$arguments)
    {
        if ($message === null || $message instanceof Subject) {
            $logger = $message;
            $message = (string) array_shift($arguments);
        }

        $code = (int) ($arguments[0] ?? 0);
        $previous = $arguments[1] ?? null;

        $message = $this->message((string) $message);

        if (isset($logger)) {
            $this->logger = $logger;
            $this->logger->error($message);
        }

        parent::__construct($message, $code, $previous instanceof Throwable ? $previous : null);
    }

    /**
     * The HTTP status code of Buckaroo's response, or null when there was no response.
     */
    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    /**
     * The raw body of Buckaroo's response, or null when there was no response.
     */
    public function getHttpBody(): ?string
    {
        return $this->httpBody;
    }

    /**
     * The response body decoded as JSON, or null when it is not a JSON object or array.
     */
    public function getJsonBody(): ?array
    {
        if ($this->httpBody === null) {
            return null;
        }

        $decoded = json_decode($this->httpBody, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * The headers of Buckaroo's response, as returned by PSR-7 getHeaders().
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @internal Called by the SDK when it creates the exception.
     */
    public function withHttpResponse(?int $status, ?string $body, array $headers = []): self
    {
        $this->httpStatus = $status;
        $this->httpBody = $body;
        $this->headers = $headers;

        return $this;
    }

    protected function message(string $message): string
    {
        return 'Buckaroo SDKExeption: ' . $message;
    }
}
