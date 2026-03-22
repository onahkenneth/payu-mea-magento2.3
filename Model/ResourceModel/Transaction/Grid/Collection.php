<?php

namespace PayU\EasyPlus\Model\ResourceModel\Transaction\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    protected function _initSelect(): Collection
    {
        parent::_initSelect();
        $this->setOrder('entity_id', 'DESC');
        return $this;
    }
}
