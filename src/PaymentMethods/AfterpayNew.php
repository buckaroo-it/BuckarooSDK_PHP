<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;
use Buckaroo\Models\Address;

class AfterpayNew extends PaymentMethod
{
    private $articlesQty = 0;

    public function getCode(): string
    {
        return PaymentMethod::AFTERPAY;
    }

    public function pay(TransactionRequest $transactionRequest): TransactionResponse
    {
        $transactionRequest->setServiceVersion(1);
        return parent::pay($transactionRequest);
    }

    public function setArticleItem(
        string $name,
        float $price,
        string $vat,
        int $qty,
        string $sku
    ): void {
        if (empty($name)) {
            $this->throwError(__METHOD__, "Empty article's name");
        }

        if (empty($qty)) {
            $this->throwError(__METHOD__, "Empty article's quantity");
        }

        if (empty($sku)) {
            $this->throwError(__METHOD__, "Empty article's SKU");
        }

        $this->articlesQty++;
        $articlesQty = (string) $this->articlesQty;

        $this->transactionRequest->setServiceParameter('Description', $name, 'Article', $articlesQty);
        $this->transactionRequest->setServiceParameter('GrossUnitPrice', (string) $price, 'Article', $articlesQty);
        $this->transactionRequest->setServiceParameter('VatPercentage', $vat, 'Article', $articlesQty);
        $this->transactionRequest->setServiceParameter('Quantity', (string) $qty, 'Article', $articlesQty);
        $this->transactionRequest->setServiceParameter('Identifier', $sku, 'Article', $articlesQty);
    }

    public function setBillingAddress(Address $address): void
    {
        //$this->billingAddress = $billingAddress;
    }

    public function setShippingAddress(Address $address): void
    {
        //$this->billingAddress = $billingAddress;
    }
}
