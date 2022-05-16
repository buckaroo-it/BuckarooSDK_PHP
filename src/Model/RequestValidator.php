<?php
declare(strict_types=1);

namespace Buckaroo\Model;

use Buckaroo\Exceptions\SdkException;

class RequestValidator
{
    public const CATEGORY_PERSON = 'Person';
    public const CATEGORY_COMPANY = 'Company';

    public function validateCategory($result,$errorMessage)
    {
        if (!in_array($result, $this->getCategories())) {
            $this->throwError($errorMessage[1]);
        }
    }

    public function getCategories(): array
    {
        return [self::CATEGORY_PERSON, self::CATEGORY_COMPANY];
    }

    protected function throwError(string $message, $value = ''): void
    {
        throw new SdkException($this->logger, "$message: '{$value}'");
    }    
}