<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\iDealQR;
use Buckaroo\Model\ServiceList;

class iDealQRParameters implements ServiceListParameter
{
    protected $serviceListParameter;
    protected ServiceList $serviceList;
    protected array $data;

    public function __construct(ServiceListParameter $serviceListParameter, array $data)
    {
        $this->data = $data;
        $this->serviceListParameter = $serviceListParameter;
    }

    public function data(): ServiceList
    {
        $this->serviceList = $this->serviceListParameter->data();

        $ideal_qr = (new iDealQR())->setProperties($this->data);

        $this->appendParameter("Amount", $ideal_qr->amount);
        $this->appendParameter("AmountIsChangeable", $ideal_qr->amountIsChangeable);
        $this->appendParameter("PurchaseId", $ideal_qr->purchaseId);
        $this->appendParameter("IsOneOff", $ideal_qr->isOneOff);
        $this->appendParameter("Expiration", $ideal_qr->expiration);
        $this->appendParameter("IsProcessing", $ideal_qr->isProcessing);
        $this->appendParameter("MinAmount", $ideal_qr->minAmount);
        $this->appendParameter("MaxAmount", $ideal_qr->maxAmount);
        $this->appendParameter("ImageSize", $ideal_qr->imageSize);

        return $this->serviceList;
    }

    private function appendParameter(string $name, $value)
    {
        if($value) {
            $this->serviceList->appendParameter([
                "Name"              => $name,
                "Value"             => $value,
                "GroupType"         => "",
                "GroupID"           => ""
            ]);
        }

        return $this;
    }
}