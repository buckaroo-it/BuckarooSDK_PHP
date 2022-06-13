<?php

namespace Buckaroo\Services\ServiceListParameters;

use Buckaroo\Models\ServiceList;
use Buckaroo\PaymentMethods\iDealQR\Models\generate;

class iDealQRParameters extends ServiceListParameter
{
    public function data(): ServiceList
    {
        $this->serviceList = $this->serviceListParameter->data();

        $ideal_qr = (new generate())->setProperties($this->data);

        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('amount'), $ideal_qr->amount);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('description'), $ideal_qr->description);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('amountIsChangeable'), $ideal_qr->amountIsChangeable);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('purchaseId'), $ideal_qr->purchaseId);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('isOneOff'), $ideal_qr->isOneOff);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('expiration'), $ideal_qr->expiration);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('isProcessing'), $ideal_qr->isProcessing);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('minAmount'), $ideal_qr->minAmount);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('maxAmount'), $ideal_qr->maxAmount);
        $this->appendParameter(null, null, $ideal_qr->serviceParameterKeyOf('imageSize'), $ideal_qr->imageSize);

        return $this->serviceList;
    }
}