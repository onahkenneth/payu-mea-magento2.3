<?php
/**
 * Copyright © PayU Financial Services. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace PayU\EasyPlus\Model\Trait;

use Magento\Sales\Api\Data\TransactionInterface;

trait GetTransactionTrait
{
    /**
     * Get payment transaction
     *
     * @param ?string $txnId
     * @return TransactionInterface|null
     */
    private function getTransaction(?string $txnId): ?TransactionInterface
    {
        $this->searchCriteriaBuilder->addFilters(
            [
                $this->filterBuilder
                    ->setField('txn_id')
                    ->setValue($txnId)
                    ->create(),
            ]
        );
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $result = $this->txnRepository->getList($searchCriteria);

        return $result->getTotalCount() > 0 ? current($result->getItems()) : null;
    }
}
