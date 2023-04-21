<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace PayU\EasyPlus\Controller\Payment;

use Exception;
use Magento\Framework\Controller\ResultFactory;
use PayU\EasyPlus\Controller\AbstractAction;

class Redirect extends AbstractAction
{
    /**
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            $url = $this->_getSession()->getCheckoutRedirectUrl();

            if ($url) {
                $this->_getSession()->unsCheckoutRedirectUrl();

                return $resultRedirect->setPath($url);
            } else {
                $this->messageManager->addErrorMessage(
                    __('Unable to redirect to PayU. Server error encountered.')
                );
            }
        } catch (Exception $exception) {
            $this->logger->debug(['error' => "Exception: " . $exception->getMessage()]);
            $this->messageManager->addExceptionMessage(
                $exception,
                __('Unable to redirect to PayU. Server error encountered')
            );
        }

        $this->_returnCustomerQuote(true, __('Unable to redirect to PayU. Server error encountered'));

        return $resultRedirect->setPath('checkout/cart');
    }
}
