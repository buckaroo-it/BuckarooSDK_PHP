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

namespace Buckaroo\Models\Payload;

use Buckaroo\Models\AdditionalParameters;
use Buckaroo\Models\ClientIP;
use Buckaroo\Models\CustomParameters;
use Buckaroo\Models\Model;

/**
 *
 */
class Payload extends Model
{
    /**
     * @var ClientIP
     */
    protected ClientIP $clientIP;
    /**
     * @var string
     */
    protected string $currency;
    /**
     * @var string
     */
    protected string $returnURL;
    /**
     * @var string
     */
    protected string $returnURLError;
    /**
     * @var string
     */
    protected string $returnURLCancel;
    /**
     * @var string
     */
    protected string $returnURLReject;
    /**
     * @var string
     */
    protected string $pushURL;
    /**
     * @var string
     */
    protected string $pushURLFailure;
    /**
     * @var string
     */
    protected string $invoice;
    /**
     * @var string
     */
    protected string $description;
    /**
     * @var string
     */
    protected string $originalTransactionKey;
    /**
     * @var string
     */
    protected string $originalTransactionReference;

    /**
     * @var string
     */
    protected string $websiteKey;

    /**
     * @var string
     */
    protected string $culture;

    /**
     * @var bool
     */
    protected bool $startRecurrent;
    /**
     * @var string
     */
    protected string $continueOnIncomplete;
    /**
     * @var string
     */
    protected string $servicesSelectableByClient;
    /**
     * @var string
     */
    protected string $servicesExcludedForClient;

    /**
     * @var AdditionalParameters
     */
    protected AdditionalParameters $additionalParameters;

    /**
     * @var CustomParameters
     */
    protected CustomParameters $customParameters;

    /**
     * @param array|null $data
     * @return Payload
     */

    public function setProperties(?array $data)
    {
        if (isset($data['customParameters']))
        {
            $this->customParameters = new CustomParameters($data['customParameters']);

            unset($data['customParameters']);
        }

        if (isset($data['additionalParameters']))
        {
            $this->additionalParameters = new AdditionalParameters($data['additionalParameters']);

            unset($data['additionalParameters']);
        }

        if (isset($data['clientIP']))
        {
            $this->clientIP = new ClientIP($data['clientIP']['address'] ?? null, $data['clientIP']['type'] ?? null);

            unset($data['clientIP']);
        }

        return parent::setProperties($data);
    }
}
