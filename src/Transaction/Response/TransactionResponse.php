<?php

declare(strict_types=1);

namespace Buckaroo\Transaction\Response;

use Buckaroo\Resources\Constants\ResponseStatus;

class TransactionResponse extends Response
{
    public function isSuccess(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_FAILED;
    }

    public function isCanceled(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER
            || $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT;
    }

    public function isAwaitingConsumer(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER;
    }

    public function isPendingProcessing(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_PENDING_PROCESSING;
    }

    public function isWaitingOnUserInput(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_USER_INPUT;
    }

    public function isRejected(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_REJECTED;
    }

    public function isValidationFailure(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_VALIDATION_FAILURE;
    }

    public function data(?string $key = null)
    {
        if($key && isset($this->data[$key]))
        {
            return $this->data[$key];
        }

        return $this->data;
    }

    public function hasRedirect(): bool
    {
        return !empty($this->data['RequiredAction']['RedirectURL'])
            && $this->data['RequiredAction']['Name'] == 'Redirect';
    }

    public function getRedirectUrl(): string
    {
        if ($this->hasRedirect()) {
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
        if (!empty($this->data['Services'][0]['Parameters'])) {
            $parameters = $this->data['Services'][0]['Parameters'];

            $params = [];

            foreach ($parameters as $key => $parameter) {
                // key to lowercase to be consistent with PaymentResult version of getServiceParameters
                $params[strtolower($parameter['Name'])] = $parameter['Value'];
            }

            return $params;
        }

        return [];
    }

    public function getCustomParameters(): array
    {
        if (!empty($this->data['CustomParameters']['List'])) {
            $parameters = $this->data['CustomParameters']['List'];

            $params = [];

            foreach ($parameters as $key => $parameter) {
                $params[$parameter['Name']] = $parameter['Value'];
            }

            return $params;
        }

        return [];
    }

    public function getAdditionalParameters(): array
    {
        if (!empty($this->data['AdditionalParameters']['AdditionalParameter'])) {
            $parameters = $this->data['AdditionalParameters']['AdditionalParameter'];

            $params = [];

            foreach ($parameters as $key => $parameter) {
                $params[$parameter['Name']] = $parameter['Value'];
            }

            return $params;
        }

        return [];
    }

    public function getTransactionKey(): string
    {
        return $this->data['Key'];
    }

    public function getPaymentKey(): string
    {
        return $this->data['PaymentKey'];
    }

    public function getToken(): string
    {
        $params = $this->getAdditionalParameters();
        return trim($params['token']);
    }

    public function getSignature(): string
    {
        $params = $this->getAdditionalParameters();
        return trim($params['signature']);
    }

    public function getAmount(): string
    {
        return $this->data['AmountDebit'];
    }

    public function getCurrency(): string
    {
        return $this->data['Currency'];
    }

    public function getInvoice(): string
    {
        return $this->data['Invoice'];
    }

    public function getStatusCode(): ?int
    {
        if (!empty($this->data['Status']['Code']['Code'])) {
            return $this->data['Status']['Code']['Code'];
        }

        return null;
    }

    public function getSubStatusCode(): ?int
    {
        if (!empty($this->data['Status']['SubCode']['Code'])) {
            return $this->data['Status']['SubCode']['Code'];
        }

        return null;
    }

    public function hasSomeError(): bool
    {
        $getError = $this->getSomeError();
        return !empty($getError);
    }

    public function getSomeError(): string
    {
        if ($this->hasError()) {
            $error = $this->getFirstError();
            return $error['ErrorMessage'];
        }

        if ($this->hasConsumerMessage()) {
            return $this->getConsumerMessage();
        }

        if ($this->hasMessage()) {
            return $this->getMessage();
        }

        if ($this->hasSubCodeMessage()) {
            return $this->getSubCodeMessage();
        }

        return '';
    }

    public function hasError(): bool
    {
        return !empty($this->data['RequestErrors']) && (
            !empty($this->data['RequestErrors']['ChannelErrors']) ||
            !empty($this->data['RequestErrors']['ServiceErrors']) ||
            !empty($this->data['RequestErrors']['ActionErrors']) ||
            !empty($this->data['RequestErrors']['ParameterErrors']) ||
            !empty($this->data['RequestErrors']['CustomParameterErrors'])
        );
    }

    public function getFirstError(): array
    {
        $errorTypes = ['ChannelErrors', 'ServiceErrors', 'ActionErrors', 'ParameterErrors', 'CustomParameterErrors'];

        if ($this->hasError()) {
            
            foreach ($errorTypes as $errorType) {
                if (!empty($this->data['RequestErrors'][$errorType])) {
                    return $this->data['RequestErrors'][$errorType][0];
                }
            }
        }

        return [];
    }

    public function hasMessage(): bool
    {
        return !empty($this->data['Message']);
    }

    public function getMessage(): string
    {
        return $this->hasMessage() ? $this->data['Message'] : '';
    }

    public function hasConsumerMessage(): bool
    {
        return !empty($this->data['ConsumerMessage']['HtmlText']);
    }

    public function getConsumerMessage(): string
    {
        if ($this->hasConsumerMessage()) {
            return $this->data['ConsumerMessage']['HtmlText'];
        }

        return '';
    }

    public function hasSubCodeMessage(): bool
    {
        return !empty($this->data['Status']['SubCode']['Description']);
    }

    public function getSubCodeMessage(): string
    {
        if ($this->hasSubCodeMessage()) {
            return $this->data['Status']['SubCode']['Description'];
        }

        return '';
    }

    public function getCustomerName(): string
    {
        return $this->data['CustomerName'];
    }

    public function get(string $key)
    {
        return $this->data[$key];
    }
}
