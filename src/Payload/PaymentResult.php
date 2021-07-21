<?php

namespace App\Buckaroo\JsonApi\Payload;

use App\Buckaroo\Base\Helper;
use Illuminate\Support\Facades\Log;
use App\Buckaroo\Constants\ResponseStatus;

class PaymentResult
{
    protected $data = [];

    public function __construct($data) {
        $this->_pushData = $data;

        // Work around, lavarel trims all paramters and this one sometimes ends with a space
        if (isset($this->_pushData['brq_statusmessage']) && isset($_POST['brq_statusmessage'])) {
            $this->_pushData['brq_statusmessage'] = $_POST['brq_statusmessage'];
        }        
    }

	public function getData($key = null)
	{
		if( empty($this->data) )
		{
            $data = $this->_pushData;

            /**
             * Rewrite keys to uppercase
             * Support Uppercase, lowercase and uppercase + lowercase for BPE push
             */
            foreach( $data as $k => $value )
            {
                $this->data[strtoupper($k)] = $value;

                // Work around, lavarel trims all paramters and this one sometimes ends with a space
                if (strtoupper($k) == 'BRQ_STATUSMESSAGE' && isset($_POST['brq_statusmessage'])) {
                    $this->data[strtoupper($k)] = $_POST['brq_statusmessage'];
                }
            }

        }

		if( !is_null($key) )
		{
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

    public function isTest()
    {
        $getBrqTest = $this->getData('BRQ_TEST');
    	return !empty($getBrqTest);
    }

    /**
     * Check the signature of the message is correct
     *
     * @return boolean
     */
    public function isValid($secretKey)
    {
        \App\Buckaroo\Base\Helper::log(__METHOD__, "|1|");

        $validateData = [];

        $data = $this->_pushData;

        foreach( $data as $key => $value )
        {
            $valueToValidate = urldecode($value);

            // payconiq validation breaks if you use urldecode();
            if (($this->getData('BRQ_TRANSACTION_METHOD') == "Payconiq") || ($this->getData('BRQ_PAYMENT_METHOD') == "Payconiq")) {
                $valueToValidate = $value;
            }
            if (
                stristr($key, 'payeremail')
            ) {
                \App\Buckaroo\Base\Helper::log(__METHOD__, "|4|");
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
                \App\Buckaroo\Base\Helper::log(__METHOD__, "|5|");
                $valueToValidate = $value;
            }

            \App\Buckaroo\Base\Helper::log(__METHOD__, "6|".var_export([$key, $valueToValidate], true));

            // sorting should be case-insensitive, so just make all keys uppercase
            $uppercaseKey = strtoupper($key);

            // add to array if
            // key should be included
            // and it is not a signature (BRQ_SIGNATURE) (AND ADD_SIGNATURE)
            if( in_array(mb_substr($uppercaseKey, 0, 4), [ 'BRQ_', 'ADD_', 'CUST' ]) && !Helper::stringContains($uppercaseKey, 'SIGNATURE') )
            {
                $validateData[$uppercaseKey] = $key . '=' . $valueToValidate;
            }
        }

        $numbers = array_flip([ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]);
        $chars = array_flip(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));

        // sort keys (first _, then numbers, then chars)
        uksort($validateData, function($a, $b) use ($numbers, $chars) {

            // get uppercase character array of strings
            $aa = str_split(strtoupper($a));
            $bb = str_split(strtoupper($b));

            // get length of strings
            $aLength = count($aa);
            $bLength = count($bb);

            // get lowest string length
            $minLength = min( $aLength, $bLength );

            // loop through characters
            for( $i=0; $i < $minLength; $i++ )
            {
                // get type of a
                $aType = 0;
                if( isset($numbers[ $aa[$i] ]) ) $aType = 1;
                if( isset($chars[ $aa[$i] ]) ) $aType = 2;

                // get type of b
                $bType = 0;
                if( isset($numbers[ $bb[$i] ]) ) $bType = 1;
                if( isset($chars[ $bb[$i] ]) ) $bType = 2;

                // first compare type
                if( $aType < $bType ) return -1;
                if( $aType > $bType ) return 1;

                // if type is the same, compare type value
                $cmp = strcasecmp($aa[$i], $bb[$i]);
                if( $aType === 1 ) $cmp = ( $aa[$i] < $bb[$i] ? -1 : ($aa[$i] > $bb[$i] ? 1 : 0) );

                // if both the same, go to the next character
                if( $cmp !== 0 ) return $cmp;
            }

            // if both strings are equal, select on string length
            return ( $aLength < $bLength ? -1 : ($aLength > $bLength ? 1 : 0) );
        });

        // join strings + the secret key
        $dataString = implode('', $validateData) . trim($secretKey);
        
        // check Buckaroo signature matches
        \App\Buckaroo\Base\Helper::log(__METHOD__, "|10|".var_export([sha1($dataString), trim($this->getData('BRQ_SIGNATURE'))], true));
        return hash_equals( sha1($dataString), trim($this->getData('BRQ_SIGNATURE')) );
    }

    /**
     * @return string
     */
	public function getTransactionKey()
	{
		return trim($this->getData('BRQ_TRANSACTIONS'));
	}

    /**
     * @return string
     */
    public function getWebsiteKey()
    {
    	return trim($this->getData('BRQ_WEBSITEKEY'));
    }

    /**
     * @return string
     */
	public function getShopId()
	{
		return trim($this->getData('ADD_SHOPID'));
	}

    /**
     * @return string
     */
	public function getShopOrderId()
	{
		return trim($this->getData('ADD_SHOPORDERID'));
	}

    /**
     * @return string
     */
    public function getShopParentOrderId()
    {
        if (trim($this->getData('ADD_SHOPPARENTORDERID'))) {
            return trim($this->getData('ADD_SHOPPARENTORDERID'));
        } else {
            return $this->getShopOrderId();
        }
    }

    /**
     * @return float
     */
	public function getAmount()
	{
		return $this->getData('BRQ_AMOUNT');
    }


    /**
     * @return string
     */
	public function getPaymentKey()
	{
		return $this->getData('BRQ_PAYMENT');
    }    
    
    /**
     * @return float
     */
	public function getAmountCredit()
	{
		return $this->getData('BRQ_AMOUNT_CREDIT');
	}    

    /**
     * @return string
     */
	public function getCurrency()
	{
		return $this->getData('BRQ_CURRENCY');
	}

    /**
     * @return string
     */
	public function getInvoice()
	{
		return $this->getData('BRQ_INVOICENUMBER');
	}

	/**
	 * Get the status code of the Buckaroo response
	 *
	 * @return int Buckaroo Response status
	 */
	public function getStatusCode()
	{
		return $this->getData('BRQ_STATUSCODE');
	}

    /**
     * Get the status subcode of the Buckaroo response
     *
     * @return string Buckaroo status subcode
     */
    public function getSubStatusCode()
    {
        return $this->getData('BRQ_STATUSCODE_DETAIL');
    }

    /**
     * Get the status subcode message of the Buckaroo response
     *
     * @return string Buckaroo status subcode
     */
    public function getSubCodeMessage()
    {
        return $this->getData('BRQ_STATUSMESSAGE');
    }

    /**
     * Get the Buckaroo key for the paymentmethod
     *
     * @return string
     */
    public function getServiceName()
    {
        return $this->getData('BRQ_TRANSACTION_METHOD');
    }

    /**
     * Get the returned service parameters
     *
     * @return array [ key => value ]
     */
    public function getServiceParameters()
    {
        $params = [];

        foreach( $this->getData() as $key => $value )
        {
            if( Helper::stringStartsWith($key, 'BRQ_SERVICE_' . strtoupper($this->getServiceName())) )
            {
                // To get key:
                // split on '_', take last part, toLowerCase
                $paramKey = strtolower(array_pop(explode('_', $key)));

                $params[ $paramKey ] = $value;
            }
        }

        return $params;
    }

    public function toArray()
    {
        return $this->getData();
    }

    /**
     * @return string
     */
    public function getDataRequest()
    {
        return trim($this->getData('BRQ_DATAREQUEST'));
    }

    /**
     * @return string
     */
    public function getPrimaryService()
    {
        return trim($this->getData('BRQ_PRIMARY_SERVICE'));
    }

    /**
     * @return string
     */
    public function getKlarnaReservationNumber()
    {
        return trim($this->getData('BRQ_SERVICE_KLARNAKP_RESERVATIONNUMBER'));
    }

    /**
     * @return  string
     */
    public function getCustomerName()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getIsTest()
    {
        return $this->data['BRQ_TEST'];
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->getStatusCode() == ResponseStatus::SUCCESS;
    }

    /**
     * @return string
     */
    public function getTransactionMethod()
    {
        return $this->getServiceName();
    }
}
