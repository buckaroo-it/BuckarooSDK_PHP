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

class BuckarooException extends Exception
{
    /**
     * @param Subject|null $logger
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */

    protected ?Subject $logger;

    public function __construct(?Subject $logger, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $this->message($message);

        $this->log($logger, $message);

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param $logger
     * @param $message
     * @return $this
     */
    private function log($logger, $message)
    {
        if ($logger)
        {
            $this->logger = $logger;
            $this->logger->error($message);
        }

        return $this;
    }

    /**
     * @param string $message
     * @return string
     */
    protected function message(string $message): string
    {
        return 'Buckaroo SDKExeption: ' . $message;
    }
}
