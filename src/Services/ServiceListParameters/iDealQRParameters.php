<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Model\iDealQR;
use Buckaroo\Model\ServiceList;

class iDealQRParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $this->serviceList = $this->serviceListParameter->data();

        $ideal_qr = (new iDealQR())->setProperties($this->data);

        $this->appendParameter(null, null,"Amount", $ideal_qr->amount);
        $this->appendParameter(null, null,"Description", $ideal_qr->description);
        $this->appendParameter(null, null,"AmountIsChangeable", $ideal_qr->amountIsChangeable);
        $this->appendParameter(null, null,"PurchaseId", $ideal_qr->purchaseId);
        $this->appendParameter(null, null,"IsOneOff", $ideal_qr->isOneOff);
        $this->appendParameter(null, null,"Expiration", $ideal_qr->expiration);
        $this->appendParameter(null, null,"IsProcessing", $ideal_qr->isProcessing);
        $this->appendParameter(null, null,"MinAmount", $ideal_qr->minAmount);
        $this->appendParameter(null, null,"MaxAmount", $ideal_qr->maxAmount);
        $this->appendParameter(null, null,"ImageSize", $ideal_qr->imageSize);

        return $this->serviceList;
    }
}