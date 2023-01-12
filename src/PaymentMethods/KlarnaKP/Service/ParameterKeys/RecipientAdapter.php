<?php
/*
 *
 *  * NOTICE OF LICENSE
 *  *
 *  * This source file is subject to the MIT License
 *  * It is available through the world-wide-web at this URL:
 *  * https://tldrlegal.com/license/mit-license
 *  * If you are unable to obtain it through the world-wide-web, please send an email
 *  * to support@buckaroo.nl so we can send you a copy immediately.
 *  *
 *  * DISCLAIMER
 *  *
 *  * Do not edit or add to this file if you wish to upgrade this module to newer
 *  * versions in the future. If you wish to customize this module for your
 *  * needs please contact support@buckaroo.nl for more information.
 *  *
 *  * @copyright Copyright (c) Buckaroo B.V.
 *  * @license   https://tldrlegal.com/license/mit-license
 *
 */

namespace Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;
use Buckaroo\Models\Model;

class RecipientAdapter extends ServiceParametersKeysAdapter implements RecipientInterface
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @param Model $model
     * @param string $type
     */
    public function __construct(Model $model, string $type)
    {
        $this->type = $type;

        parent::__construct($model);
    }

    /**
     * @param $propertyName
     * @return string
     */
    public function serviceParameterKeyOf($propertyName): string
    {
        $propertyName = (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);

        return $this->type . $propertyName;
    }
}
