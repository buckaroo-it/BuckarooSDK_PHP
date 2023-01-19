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

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys;

use Buckaroo\Models\Model;

class ServiceAdapter extends \Buckaroo\Models\Adapters\ServiceParametersKeysAdapter
{
    /**
     * @var string
     */
    protected string $prefix = '';

    /**
     * @param string $prefix
     * @param Model $model
     */
    public function __construct(string $prefix, Model $model)
    {
        $this->prefix = $prefix;

        parent::__construct($model);
    }

    /**
     * @param $propertyName
     * @return string
     */
    public function serviceParameterKeyOf($propertyName): string
    {
        $name = (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);

        return $this->prefix . $name;
    }
}
