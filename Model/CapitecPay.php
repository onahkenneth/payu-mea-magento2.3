<?php
/**
 * Copyright © PayU Financial Services. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace PayU\EasyPlus\Model;

/**
 * Payment model for payment method CapitecPay
 */
class CapitecPay extends AbstractPayment
{
    const CODE = 'payumea_capitec_pay';

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = self::CODE;
}
