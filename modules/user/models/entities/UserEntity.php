<?php

namespace modules\user\models\entities;

use models\Entity;

class UserEntity extends Entity
{
    private $_id;
    private $_wallet;
    private $_name;


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

    public function getWallet()
    {
        return $this->_wallet;
    }

    public function setWallet($wallet)
    {
        $this->_wallet = $wallet;
    }

}
