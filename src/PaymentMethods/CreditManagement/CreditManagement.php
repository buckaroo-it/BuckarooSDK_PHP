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

namespace Buckaroo\PaymentMethods\CreditManagement;

use Buckaroo\Models\Payload\PayPayload;
use Buckaroo\PaymentMethods\CreditManagement\Models\AddOrUpdateProductLines;
use Buckaroo\PaymentMethods\CreditManagement\Models\CreditNote;
use Buckaroo\PaymentMethods\CreditManagement\Models\Debtor;
use Buckaroo\PaymentMethods\CreditManagement\Models\DebtorFile;
use Buckaroo\PaymentMethods\CreditManagement\Models\DebtorInfo;
use Buckaroo\PaymentMethods\CreditManagement\Models\Invoice;
use Buckaroo\PaymentMethods\CreditManagement\Models\MultipleInvoiceInfo;
use Buckaroo\PaymentMethods\CreditManagement\Models\PaymentPlan;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PaymentMethod;

class CreditManagement extends PaymentMethod implements Combinable
{
    protected string $paymentName = 'CreditManagement3';
    protected array $requiredConfigFields = ['currency'];

    public function createInvoice()
    {
        $invoice = new Invoice($this->payload);

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('CreateInvoice', $invoice);

        return $this->dataRequest();
    }

    public function createCombinedInvoice()
    {
        $invoice = new Invoice($this->payload);

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('CreateCombinedInvoice', $invoice);

        return $this->dataRequest();
    }

    public function createCreditNote()
    {
        $creditNote = new CreditNote($this->payload);

        $payPayload = new PayPayload($this->payload);

        $this->request->setPayload($payPayload);

        $this->setServiceList('CreateCreditNote', $creditNote);

        return $this->dataRequest();
    }

    public function addOrUpdateDebtor()
    {
        $debtor = new Debtor($this->payload);

        $this->setServiceList('AddOrUpdateDebtor', $debtor);

        return $this->dataRequest();
    }

    public function createPaymentPlan()
    {
        $paymentPlan = new PaymentPlan($this->payload);

        $this->setServiceList('CreatePaymentPlan', $paymentPlan);

        $this->request->setData('Description', $this->payload['description'] ?? null);

        return $this->dataRequest();
    }

    public function terminatePaymentPlan()
    {
        $paymentPlan = new PaymentPlan($this->payload);

        $this->setServiceList('TerminatePaymentPlan', $paymentPlan);

        return $this->dataRequest();
    }

    public function pauseInvoice()
    {
        $this->request->setData('Invoice', $this->payload['invoice'] ?? null);

        $this->setServiceList('PauseInvoice');

        return $this->dataRequest();
    }

    public function unpauseInvoice()
    {
        $this->request->setData('Invoice', $this->payload['invoice'] ?? null);

        $this->setServiceList('UnPauseInvoice');

        return $this->dataRequest();
    }

    public function invoiceInfo()
    {
        $multipleInvoices = new MultipleInvoiceInfo($this->payload);

        $this->request->setData('Invoice', $this->payload['invoice'] ?? null);

        $this->setServiceList('InvoiceInfo', $multipleInvoices);

        return $this->dataRequest();
    }

    public function debtorInfo()
    {
        $debtorInfo = new DebtorInfo($this->payload);

        $this->setServiceList('DebtorInfo', $debtorInfo);

        return $this->dataRequest();
    }

    public function addOrUpdateProductLines()
    {
        $addOrUpdateProductLines = new AddOrUpdateProductLines($this->payload);

        $this->setServiceList('AddOrUpdateProductLines', $addOrUpdateProductLines);

        return $this->dataRequest();
    }

    public function resumeDebtorFile()
    {
        $debtor_file = new DebtorFile($this->payload);

        $this->setServiceList('ResumeDebtorFile', $debtor_file);

        return $this->dataRequest();
    }

    public function pauseDebtorFile()
    {
        $debtor_file = new DebtorFile($this->payload);

        $this->setServiceList('PauseDebtorFile', $debtor_file);

        return $this->dataRequest();
    }
}
