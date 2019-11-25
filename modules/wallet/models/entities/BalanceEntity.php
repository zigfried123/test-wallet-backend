<?php

namespace modules\wallet\models\entities;

use models\Entity;

class BalanceEntity extends Entity
{
    const TRANSACTION_TYPE = [1 => 'debit', 2 => 'credit'];
    const REASON = [1 => 'stock', 2 => 'refund'];

    private $_id;
    private $_sum;
    private $_walletId;
    private $_dateCreated;
    private $_transactionType;
    private $_reason;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getSum()
    {
        return $this->_sum;
    }

    public function setSum($sum)
    {
        $this->_sum = $sum;
    }

    public function getWalletId()
    {
        return $this->_walletId;
    }

    public function setWalletId($walletId)
    {
        $this->_walletId = $walletId;
    }

    public function getDateCreated()
    {
        return $this->_dateCreated;
    }

    public function setDateCreated($date)
    {
        $this->_dateCreated = $date;
    }

    public function getTransactionType()
    {
        return $this->_transactionType;
    }

    public function setTransactionType($type)
    {
        $this->_transactionType = $type;
    }

    public function getReason()
    {
        return $this->_reason;
    }

    public function setReason($reason)
    {
        $this->_reason = $reason;
    }
}
