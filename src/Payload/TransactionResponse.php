<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Payload;

use Buckaroo\SDK\Payload\Response;
use Buckaroo\SDK\Helpers\Constants\ResponseStatus;
use Buckaroo\SDK\Helpers\Base;

class TransactionResponse extends Response
{
    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS;
    }

    /**
     * @return boolean
     */
    public function isCanceled()
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER
            || $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT;
    }

    /**
     * @return boolean
     */
    public function isAwaitingConsumer()
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER;
    }

    /**
     * @return boolean
     */
    public function isPendingProcessing()
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_PENDING_PROCESSING;
    }

    public function isWaitingOnUserInput()
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_USER_INPUT;
    }

    /**
     * @return boolean
     */
    public function hasRedirect()
    {
        return !empty($this->data['RequiredAction']['RedirectURL'])
            && $this->data['RequiredAction']['Name'] == 'Redirect';
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        if ($this->hasRedirect()) {
            return $this->data['RequiredAction']['RedirectURL'];
        }

        return '';
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->data['Services'][0]['Name'];
    }

    /**
     * @return string
     */
    public function getServiceAction()
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

    /**
     * @return array [ name => value ]
     */
    public function getCustomParameters()
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

    /**
     * @return array [ name => value ]
     */
    public function getAdditionalParameters()
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

    /**
     * @return string
     */
    public function getTransactionKey()
    {
        return $this->data['Key'];
    }

    /**
     * @return string
     */
    public function getPaymentKey()
    {
        return $this->data['PaymentKey'];
    }

    /**
     * @return string
     */
    public function getToken()
    {
        $params = $this->getAdditionalParameters();
        return trim($params['token']);
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        $params = $this->getAdditionalParameters();
        return trim($params['signature']);
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->data['AmountDebit'];
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->data['Currency'];
    }

    /**
     * @return string
     */
    public function getInvoice()
    {
        return $this->data['Invoice'];
    }

    /**
     * Get the status code of the Buckaroo response
     *
     * @return int Buckaroo Response status
     */
    public function getStatusCode()
    {
        if (!empty($this->data['Status']['Code']['Code'])) {
            return $this->data['Status']['Code']['Code'];
        }

        return null;
    }

    /**
     * Get the status subcode of the Buckaroo response
     *
     * @return string Buckaroo status subcode
     */
    public function getSubStatusCode()
    {
        if (!empty($this->data['Status']['SubCode']['Code'])) {
            return $this->data['Status']['SubCode']['Code'];
        }

        return null;
    }

    /**
     * Check if there is an error in the Response
     *
     * @return boolean
     */
    public function hasSomeError()
    {
        $getError = $this->getSomeError();
        return !empty($getError);
    }

    /**
     * Try all possible methods to get an error message, return first one
     *
     * @return string
     */
    public function getSomeError()
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

    /**
     * @return boolean
     */
    public function hasError()
    {
        return !empty($this->data['RequestErrors']) && (
            !empty($this->data['RequestErrors']['ChannelErrors']) ||
            !empty($this->data['RequestErrors']['ServiceErrors']) ||
            !empty($this->data['RequestErrors']['ActionErrors']) ||
            !empty($this->data['RequestErrors']['ParameterErrors']) ||
            !empty($this->data['RequestErrors']['CustomParameterErrors'])
        );
    }

    /**
     * @return array
     */
    public function getFirstError()
    {
        if ($this->hasError()) {
            if (!empty($this->data['RequestErrors']['ChannelErrors'])) {
                return $this->data['RequestErrors']['ChannelErrors'][0];
            }

            if (!empty($this->data['RequestErrors']['ServiceErrors'])) {
                return $this->data['RequestErrors']['ServiceErrors'][0];
            }

            if (!empty($this->data['RequestErrors']['ActionErrors'])) {
                return $this->data['RequestErrors']['ActionErrors'][0];
            }

            if (!empty($this->data['RequestErrors']['ParameterErrors'])) {
                return $this->data['RequestErrors']['ParameterErrors'][0];
            }

            if (!empty($this->data['RequestErrors']['CustomParameterErrors'])) {
                return $this->data['RequestErrors']['CustomParameterErrors'][0];
            }
        }

        return [];
    }

    /**
     * @return boolean
     */
    public function hasMessage()
    {
        return !empty($this->data['Message']);
    }

    /**
     * @return  string
     */
    public function getMessage()
    {
        return $this->hasMessage() ? $this->data['Message'] : '';
    }

    /**
     * Check if the response has a user-friendly message
     *
     * @return boolean
     */
    public function hasConsumerMessage()
    {
        return !empty($this->data['ConsumerMessage']['HtmlText']);
    }

    /**
     * @return string
     */
    public function getConsumerMessage()
    {
        if ($this->hasConsumerMessage()) {
            return $this->data['ConsumerMessage']['HtmlText'];
        }

        return '';
    }

    /**
     * Check if the response has a subcode message
     *
     * @return boolean
     */
    public function hasSubCodeMessage()
    {
        return !empty($this->data['Status']['SubCode']['Description'])
            && Base::stringContains($this->data['Status']['SubCode']['Description'], ':');
    }

    /**
     * @return string
     */
    public function getSubCodeMessage()
    {
        if ($this->hasSubCodeMessage()) {
            $parts = explode(':', $this->data['Status']['SubCode']['Description']);
            return trim(array_pop($parts));
        }

        return '';
    }

    public function getSubCodeMessageFull()
    {
        return $this->data['Status']['SubCode']['Description'];
    }

    /**
     * @return  string
     */
    public function getCustomerName()
    {
        return $this->data['CustomerName'];
    }
}
