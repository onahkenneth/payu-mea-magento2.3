<?php
/**
 * Copyright © PayU Financial Services. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace PayU\EasyPlus\Model;

/**
 * Payment model for payment method AirtelMoney
 */
class AirtelMoney extends AbstractPayment
{
    const CODE = 'payumea_airtel_money';

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = self::CODE;
}
