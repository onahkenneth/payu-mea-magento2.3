<?php
/**
 * Copyright © 2022 PayU Financial Services. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace PayU\EasyPlus\Model\Trait;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
use PayU\EasyPlus\Model\Trait\GetTransactionTrait;

/**
 * class TransactionUpdateOperation
 * @package PayU\Gateway\Model\Payment\Operations
 */
trait UpdateTransactionTrait
{
    use GetTransactionTrait;

    /**
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param BuilderInterface $txnBuilder
     * @param TransactionRepositoryInterface $txnRepository
     */
    public function __construct(
        protected readonly FilterBuilder $filterBuilder,
        protected readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        protected readonly BuilderInterface $txnBuilder,
        protected readonly TransactionRepositoryInterface $txnRepository
    ) {}

    /**
     * @param OrderInterface $order
     * @param DataObject $txnInfo
     * @return void
     * @throws LocalizedException
     */
    public function updateTxn(OrderInterface $order, DataObject $txnInfo): void
    {
        $payment = $order->getPayment();
        $parentTransaction = $this->getTransaction($payment->getParentTransactionId());
        $currentTransaction = $this->getTransaction($payment->getTransactionId());

        if ($currentTransaction && $parentTransaction) {
            if ((int) $currentTransaction->getParentId() !== (int) $parentTransaction->getTransactionId()) {
                $currentTransaction->setParentId($parentTransaction->getTransactionId());
            }

            $formattedPrice = $order->getBaseCurrency()->formatTxt(
                $order->getGrandTotal()
            );
            $message = __('The order transaction amount is %1.', $formattedPrice);

            if ($payment->getBaseAmountCanceled()) {
                $parentTransaction->setIsClosed(true);
            }

            $payment->addTransactionCommentsToOrder(
                $parentTransaction,
                $message
            );
            $this->txnRepository->save($parentTransaction);
            $this->txnRepository->save($currentTransaction);
        } else {
            $payment->setIsTransactionClosed(true);

            $transactionBuilder = $this->txnBuilder->setPayment($payment)
                ->setOrder($order)
                ->setFailSafe(true)
                ->setTransactionId($txnInfo->getTranxId());
            $transaction = $transactionBuilder->build(TransactionInterface::TYPE_ORDER);
            $data = $transaction?->getAdditionalInformation();
            $transaction?->setAdditionalInformation(
                Order\Payment\Transaction::RAW_DETAILS,
                ($data[Order\Payment\Transaction::RAW_DETAILS] ?? []) + $txnInfo->getPaymentData()
            );
            $this->txnRepository->save($transaction);
        }
    }
}
