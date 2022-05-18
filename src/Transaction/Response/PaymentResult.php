<?php

namespace Buckaroo\Transaction\Response;

use Buckaroo\Helpers\Base;
use Buckaroo\Helpers\Constants\ResponseStatus;

class PaymentResult
{
    protected array $data = [];

    public function __construct($data)
    {
        $this->_pushData = $data;
    }

    public function getData(?string $key = null)
    {
        if (empty($this->data)) {
            $data = $this->_pushData;

            /**
             * Rewrite keys to uppercase
             * Support Uppercase, lowercase and uppercase + lowercase for BPE push
             */
            foreach ($data as $k => $value) {
                $this->data[strtoupper($k)] = $value;
            }
        }

        if (!is_null($key)) {
            return $this->offsetGet($key);
        }

        return $this->data;
    }


    public function offsetSet($offset, $value)
    {
        throw new \Exception("Can't set a value of a PaymentResult");
    }


    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }


    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }


    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function isTest(): bool
    {
        $getBrqTest = $this->getData('BRQ_TEST');
        return !empty($getBrqTest);
    }

    public function isValid(string $secretKey): bool
    {
        $validateData = [];

        $data = $this->_pushData;

        foreach ($data as $key => $value) {
            $valueToValidate = urldecode($value);

            // payconiq validation breaks if you use urldecode();
            if (
                ($this->getData('BRQ_TRANSACTION_METHOD') == "Payconiq")
                || ($this->getData('BRQ_PAYMENT_METHOD') == "Payconiq")
            ) {
                $valueToValidate = $value;
            }

            if (
                stristr($key, 'payeremail')
            ) {
                $valueToValidate = str_replace(' ', '+', $valueToValidate);
            }

            if (
                stristr($key, 'customer_name') ||
                stristr($key, 'payerfirstname') ||
                stristr($key, 'payerlastname') ||
                stristr($key, 'accountholdername') ||
                stristr($key, 'customeraccountname') ||
                stristr($key, 'consumername')
            ) {
                $valueToValidate = $value;
            }

            // sorting should be case-insensitive, so just make all keys uppercase
            $uppercaseKey = strtoupper($key);

            // add to array if
            // key should be included
            // and it is not a signature (BRQ_SIGNATURE) (AND ADD_SIGNATURE)
            if (
                in_array(mb_substr($uppercaseKey, 0, 4), [ 'BRQ_', 'ADD_', 'CUST' ])
                && !Base::stringContains($uppercaseKey, 'SIGNATURE')
            ) {
                $validateData[$uppercaseKey] = $key . '=' . $valueToValidate;
            }
        }

        $numbers = array_flip([ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]);
        $chars = array_flip(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));

        // sort keys (first _, then numbers, then chars)
        uksort($validateData, function ($a, $b) use ($numbers, $chars) {

            // get uppercase character array of strings
            $aa = str_split(strtoupper($a));
            $bb = str_split(strtoupper($b));

            // get length of strings
            $aLength = count($aa);
            $bLength = count($bb);

            // get lowest string length
            $minLength = min($aLength, $bLength);

            // loop through characters
            for ($i = 0; $i < $minLength; $i++) {
                // get type of a
                $aType = 0;
                if (isset($numbers[ $aa[$i] ])) {
                    $aType = 1;
                }
                if (isset($chars[ $aa[$i] ])) {
                    $aType = 2;
                }

                // get type of b
                $bType = 0;
                if (isset($numbers[ $bb[$i] ])) {
                    $bType = 1;
                }
                if (isset($chars[ $bb[$i] ])) {
                    $bType = 2;
                }

                // first compare type
                if ($aType < $bType) {
                    return -1;
                }
                if ($aType > $bType) {
                    return 1;
                }

                // if type is the same, compare type value
                $cmp = strcasecmp($aa[$i], $bb[$i]);
                if ($aType === 1) {
                    $cmp = ( $aa[$i] < $bb[$i] ? -1 : ($aa[$i] > $bb[$i] ? 1 : 0) );
                }

                // if both the same, go to the next character
                if ($cmp !== 0) {
                    return $cmp;
                }
            }

            // if both strings are equal, select on string length
            return ( $aLength < $bLength ? -1 : ($aLength > $bLength ? 1 : 0) );
        });

        // join strings + the secret key
        $dataString = implode('', $validateData) . trim($secretKey);

        // check Buckaroo signature matches
        return hash_equals(sha1($dataString), trim($this->getData('BRQ_SIGNATURE')));
    }

    public function getTransactionKey(): ?string
    {
        return trim($this->getData('BRQ_TRANSACTIONS'));
    }

    public function getWebsiteKey(): ?string
    {
        return trim($this->getData('BRQ_WEBSITEKEY'));
    }

    public function getToken(): ?string
    {
        return trim($this->getData('ADD_TOKEN'));
    }

    public function getSignature(): ?string
    {
        return trim($this->getData('ADD_SIGNATURE'));
    }

    public function getAmount(): ?string
    {
        return $this->getData('BRQ_AMOUNT');
    }

    public function getAmountCredit(): ?string
    {
        return $this->getData('BRQ_AMOUNT_CREDIT');
    }

    public function getCurrency(): ?string
    {
        return $this->getData('BRQ_CURRENCY');
    }

    public function getInvoice(): ?string
    {
        return $this->getData('BRQ_INVOICENUMBER');
    }

    public function getOrder(): ?string
    {
        return $this->getData('BRQ_ORDERNUMBER');
    }

    public function getMutationType(): ?string
    {
        return $this->getData('BRQ_MUTATIONTYPE');
    }

    public function getTransactionType(): ?string
    {
        return $this->getData('BRQ_TRANSACTION_TYPE');
    }

    public function getStatusCode(): ?string
    {
        return $this->getData('BRQ_STATUSCODE');
    }

    public function getSubStatusCode(): ?string
    {
        return $this->getData('BRQ_STATUSCODE_DETAIL');
    }

    public function getSubCodeMessage(): ?string
    {
        return $this->getData('BRQ_STATUSMESSAGE');
    }

    public function getMethod(): ?string
    {
        return $this->getData('BRQ_TRANSACTION_METHOD') ?? $this->getData('BRQ_PAYMENT_METHOD');
    }

    public function getServiceParameters(): array
    {
        $params = [];

        foreach ($this->getData() as $key => $value) {
            if (Base::stringStartsWith($key, 'BRQ_SERVICE_' . strtoupper($this->getMethod()))) {
                // To get key:
                // split on '_', take last part, toLowerCase
                $paramKey = strtolower(array_pop(explode('_', $key)));

                $params[ $paramKey ] = $value;
            }
        }

        return $params;
    }

    public function toArray(): array
    {
        return $this->getData();
    }

    public function getDataRequest(): ?string
    {
        return trim($this->getData('BRQ_DATAREQUEST'));
    }

    public function getPrimaryService(): ?string
    {
        return trim($this->getData('BRQ_PRIMARY_SERVICE'));
    }

    public function getKlarnaReservationNumber(): ?string
    {
        return trim($this->getData('BRQ_SERVICE_KLARNAKP_RESERVATIONNUMBER'));
    }

    public function getCustomerName(): ?string
    {
        return "";
    }

    public function getIsTest(): ?string
    {
        return $this->data['BRQ_TEST'];
    }

    public function isSuccess(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS;
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
}
