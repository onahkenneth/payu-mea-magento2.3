<?php
/**
 * PayU_EasyPlus payment method model
 *
 * @category    PayU
 * @package     PayU_EasyPlus
 * @author      Kenneth Onah
 * @copyright   PayU South Africa (http://payu.co.za)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace PayU\EasyPlus\Model;

/**
 * Payment model for payment method Masterpass
 */
class MasterPass extends AbstractPayment
{
    const CODE = 'payumea_masterpass';

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = self::CODE;
}
