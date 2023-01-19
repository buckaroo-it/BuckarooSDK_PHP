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

namespace Buckaroo\Transaction\Request;

use Buckaroo\Models\Model;
use Buckaroo\Models\Services;
use Buckaroo\Resources\Arrayable;

class TransactionRequest extends Request
{
    /**
     *
     */
    public function __construct()
    {
        $this->data['ClientUserAgent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function setPayload(Model $model)
    {
        foreach ($model->toArray() as $key => $value)
        {
            $this->data[$model->serviceParameterKeyOf($key)] = $value;
        }

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @return Services
     */
    public function getServices() : Services
    {
        $this->data['Services'] = $this->data['Services'] ?? new Services;

        return $this->data['Services'];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        foreach ($this->data as $key => $value)
        {
            if (is_a($value, Arrayable::class))
            {
                $this->data[$key] = $value->toArray();
            }
        }

        return $this->data;
    }
}
