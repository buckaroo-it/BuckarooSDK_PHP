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

namespace Buckaroo\PaymentMethods;

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\Services\PayloadService;
use Buckaroo\Transaction\Client;
use Buckaroo\Transaction\Response\TransactionResponse;

/**
 * @method TransactionResponse pay(array $data)
 * @method TransactionResponse refund(array $data)
 * @method TransactionResponse payRecurrent(array $data)
 * @method TransactionResponse extraInfo(array $data)
 * @method TransactionResponse capture(array $data)
 * @method TransactionResponse cancelAuthorize(array $data)
 * @method TransactionResponse authorize(array $data)
 * @method TransactionResponse payEncrypted(array $data)
 * @method TransactionResponse payWithToken(array $data)
 * @method TransactionResponse authorizeWithToken(array $data)
 * @method TransactionResponse authenticate(array $data)
 * @method TransactionResponse createWallet(array $data)
 * @method TransactionResponse updateWallet(array $data)
 * @method TransactionResponse getInfo(array $data)
 * @method TransactionResponse release(array $data)
 * @method TransactionResponse deposit(array $data)
 * @method TransactionResponse reserve(array $data)
 * @method TransactionResponse withdrawal(array $data)
 * @method TransactionResponse cancel(array $data)
 * @method TransactionResponse payWithSecurityCode(array $data)
 * @method TransactionResponse authorizeEncrypted(array $data)
 * @method TransactionResponse authorizeWithSecurityCode(array $data)
 * @method TransactionResponse createInvoice(array $data)
 * @method TransactionResponse createCombined(array $data)
 * @method TransactionResponse createCombinedInvoice(array $data)
 * @method TransactionResponse createCreditNote(array $data)
 * @method TransactionResponse addOrUpdateDebtor(array $data)
 * @method TransactionResponse createPaymentPlan(array $data)
 * @method TransactionResponse terminatePaymentPlan(array $data)
 * @method TransactionResponse pauseInvoice(array $data)
 * @method TransactionResponse unpauseInvoice(array $data)
 * @method TransactionResponse invoiceInfo(array $data)
 * @method TransactionResponse debtorInfo(array $data)
 * @method TransactionResponse addOrUpdateProductLines(array $data)
 * @method TransactionResponse resumeDebtorFile(array $data)
 * @method TransactionResponse pauseDebtorFile(array $data)
 * @method TransactionResponse issuerList()
 * @method TransactionResponse createMandate(array $data)
 * @method TransactionResponse status(array $data)
 * @method TransactionResponse modifyMandate(array $data)
 * @method TransactionResponse cancelMandate(array $data)
 * @method TransactionResponse payRemainder(array $data)
 * @method TransactionResponse payRemainderEncrypted(array $data)
 * @method TransactionResponse generate(array $data)
 * @method TransactionResponse identify(array $data)
 * @method TransactionResponse instantRefund(array $data)
 * @method TransactionResponse verify(array $data)
 * @method TransactionResponse login(array $data)
 * @method TransactionResponse payInInstallments(array $data)
 * @method TransactionResponse split(array $data)
 * @method TransactionResponse transfer(array $data)
 * @method TransactionResponse refundSupplementary(array $data)
 * @method TransactionResponse paymentInvitation(array $data)
 * @method TransactionResponse payWithEmandate(array $data)
 * @method TransactionResponse update(array $data)
 * @method TransactionResponse updateCombined(array $data)
 * @method TransactionResponse stop(array $data)
 * @method TransactionResponse info(array $data)
 * @method TransactionResponse deletePaymentConfig(array $data)
 * @method TransactionResponse pause(array $data)
 * @method TransactionResponse resume(array $data)
 * @method TransactionResponse payOneClick(array $data)
 * @method TransactionResponse setServiceVersion(int $versionId)
 */
class PaymentFacade
{
    /**
     * @var PaymentMethod
     */
    private PaymentMethod $paymentMethod;

    /**
     * @var bool
     */
    private bool $isManually = false;

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @param Client $client
     * @param string $method
     */
    public function __construct(Client $client, ?string $method)
    {
        $this->client = $client;

        $this->paymentMethod = PaymentMethodFactory::get($client, $method);
    }

    /**
     * @return $this
     */
    public function manually()
    {
        $this->paymentMethod->manually(true);

        return $this;
    }

    /**
     * @param $combinablePayment
     * @return $this
     */
    public function combine($combinablePayment)
    {
        if (is_array($combinablePayment))
        {
            foreach ($combinablePayment as $combinable_payment)
            {
                $this->combine($combinable_payment);
            }

            return $this;
        }

        if ($combinablePayment instanceof Combinable)
        {
            $this->paymentMethod->combinePayment($combinablePayment);
        }

        return $this;
    }

    /**
     * @return PaymentMethod
     */
    public function paymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws BuckarooException
     */
    public function __call(?string $name, array $arguments)
    {
        if (method_exists($this->paymentMethod, $name)) {
            if($name === 'setServiceVersion') {
                $this->paymentMethod->setServiceVersion($arguments[0]);

                return $this;
            }

            $this->paymentMethod->setPayload((new PayloadService($arguments[0] ?? []))->toArray());

            return $this->paymentMethod->$name();
        }

        throw new BuckarooException(
            $this->client->config()->getLogger(),
            "Payment method " .
            $name . " on payment " .
            $this->paymentMethod->paymentName() . " you requested does not exist."
        );
    }
}
