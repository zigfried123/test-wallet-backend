<?php

namespace modules\wallet\models\entities;

use models\Entity;

class WalletEntity extends Entity
{
    const CURRENCIES = [1 => 'RUB', 2 => 'USD'];

    private $_id;
    private $_currency;
    private $_name;
    private $_balance;
    private $_user;
    private $_userId;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
       $this->_id = $id;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setCurrency($currency = 1)
    {
        $this->_currency = $currency;
    }

    public function getCurrency()
    {
        return $this->_currency;
    }

    public function setBalance($balance)
    {
        if(!$balance instanceof BalanceEntity) return false;

        $this->_balance = $balance;
    }

    public function getBalance()
    {
        return $this->_balance;
    }

    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    public function getUserId()
    {
       return $this->_userId;
    }

    public function setUser($user)
    {
        if(!$user instanceof UserEntity) return false;

        $this->_user = $user;
    }

    public function getUser()
    {
        return $this->_user;
    }

}
