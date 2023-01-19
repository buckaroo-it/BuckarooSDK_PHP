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

namespace Buckaroo\Transaction\Response;

use Buckaroo\Resources\Constants\ResponseStatus;

class TransactionResponse extends Response
{
    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS;
    }

    /**
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_FAILED;
    }

    /**
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER
            || $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT;
    }

    /**
     * @return bool
     */
    public function isAwaitingConsumer(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER;
    }

    /**
     * @return bool
     */
    public function isPendingProcessing(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_PENDING_PROCESSING;
    }

    /**
     * @return bool
     */
    public function isWaitingOnUserInput(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_USER_INPUT;
    }

    /**
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_REJECTED;
    }

    /**
     * @return bool
     */
    public function isValidationFailure(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_VALIDATION_FAILURE;
    }

    /**
     * @param string|null $key
     * @return array|mixed
     */
    public function data(?string $key = null)
    {
        if ($key && isset($this->data[$key]))
        {
            return $this->data[$key];
        }

        return $this->data;
    }

    /**
     * @return bool
     */
    public function hasRedirect(): bool
    {
        return ! empty($this->data['RequiredAction']['RedirectURL'])
            && $this->data['RequiredAction']['Name'] == 'Redirect';
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        if ($this->hasRedirect())
        {
            return $this->data['RequiredAction']['RedirectURL'];
        }

        return '';
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->data['Services'][0]['Name'];
    }

    /**
     * @return string
     */
    public function getServiceAction(): string
    {
        return $this->data['Services'][0]['Action'];
    }

    /**
     * Get the returned service parameters
     *
     * @return array [ name => value ]
     */
    public function getServiceParameters()
    {
        if (! empty($this->data['Services'][0]['Parameters']))
        {
            $parameters = $this->data['Services'][0]['Parameters'];

            $params = [];

            foreach ($parameters as $key => $parameter)
            {
                // key to lowercase to be consistent with PaymentResult version of getServiceParameters
                $params[strtolower($parameter['Name'])] = $parameter['Value'];
            }

            return $params;
        }

        return [];
    }

    /**
     * @return array
     */
    public function getCustomParameters(): array
    {
        if (! empty($this->data['CustomParameters']['List']))
        {
            $parameters = $this->data['CustomParameters']['List'];

            $params = [];

            foreach ($parameters as $key => $parameter)
            {
                $params[$parameter['Name']] = $parameter['Value'];
            }

            return $params;
        }

        return [];
    }

    /**
     * @return array
     */
    public function getAdditionalParameters(): array
    {
        if (! empty($this->data['AdditionalParameters']['AdditionalParameter']))
        {
            $parameters = $this->data['AdditionalParameters']['AdditionalParameter'];

            $params = [];

            foreach ($parameters as $key => $parameter)
            {
                $params[$parameter['Name']] = $parameter['Value'];
            }

            return $params;
        }

        return [];
    }

    /**
     * @return string
     */
    public function getTransactionKey(): string
    {
        return $this->data['Key'];
    }

    /**
     * @return string
     */
    public function getPaymentKey(): string
    {
        return $this->data['PaymentKey'];
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        $params = $this->getAdditionalParameters();

        return trim($params['token']);
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        $params = $this->getAdditionalParameters();

        return trim($params['signature']);
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return (string)$this->data['AmountDebit'];
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->data['Currency'];
    }

    /**
     * @return string
     */
    public function getInvoice(): string
    {
        return $this->data['Invoice'];
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        if (! empty($this->data['Status']['Code']['Code']))
        {
            return $this->data['Status']['Code']['Code'];
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getSubStatusCode(): ?int
    {
        if (! empty($this->data['Status']['SubCode']['Code']))
        {
            return $this->data['Status']['SubCode']['Code'];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasSomeError(): bool
    {
        $getError = $this->getSomeError();

        return ! empty($getError);
    }

    /**
     * @return string
     */
    public function getSomeError(): string
    {
        if ($this->hasError())
        {
            $error = $this->getFirstError();

            return $error['ErrorMessage'];
        }

        if ($this->hasConsumerMessage())
        {
            return $this->getConsumerMessage();
        }

        if ($this->hasMessage())
        {
            return $this->getMessage();
        }

        if ($this->hasSubCodeMessage())
        {
            return $this->getSubCodeMessage();
        }

        return '';
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return ! empty($this->data['RequestErrors']) && (
            ! empty($this->data['RequestErrors']['ChannelErrors']) ||
            ! empty($this->data['RequestErrors']['ServiceErrors']) ||
            ! empty($this->data['RequestErrors']['ActionErrors']) ||
            ! empty($this->data['RequestErrors']['ParameterErrors']) ||
            ! empty($this->data['RequestErrors']['CustomParameterErrors'])
        );
    }

    /**
     * @return array
     */
    public function getFirstError(): array
    {
        $errorTypes = ['ChannelErrors', 'ServiceErrors', 'ActionErrors', 'ParameterErrors', 'CustomParameterErrors'];

        if ($this->hasError())
        {
            foreach ($errorTypes as $errorType)
            {
                if (! empty($this->data['RequestErrors'][$errorType]))
                {
                    return $this->data['RequestErrors'][$errorType][0];
                }
            }
        }

        return [];
    }

    /**
     * @return bool
     */
    public function hasMessage(): bool
    {
        return ! empty($this->data['Message']);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->hasMessage() ? $this->data['Message'] : '';
    }

    /**
     * @return bool
     */
    public function hasConsumerMessage(): bool
    {
        return ! empty($this->data['ConsumerMessage']['HtmlText']);
    }

    /**
     * @return string
     */
    public function getConsumerMessage(): string
    {
        if ($this->hasConsumerMessage())
        {
            return $this->data['ConsumerMessage']['HtmlText'];
        }

        return '';
    }

    /**
     * @return bool
     */
    public function hasSubCodeMessage(): bool
    {
        return ! empty($this->data['Status']['SubCode']['Description']);
    }

    /**
     * @return string
     */
    public function getSubCodeMessage(): string
    {
        if ($this->hasSubCodeMessage())
        {
            return $this->data['Status']['SubCode']['Description'];
        }

        return '';
    }

    /**
     * @return string
     */
    public function getCustomerName(): string
    {
        return $this->data['CustomerName'];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key];
    }
}
