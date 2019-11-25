<?php

namespace modules\wallet\models\entities;

use models\Entity;

class RateEntity extends Entity
{
    private $_id;
    private $_usd;
    private $_rub;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getRub()
    {
        return $this->_rub;
    }

    public function setRub($rub)
    {
        $this->_rub = $rub;
    }

    public function getUsd()
    {
        return $this->_usd;
    }

    public function setUsd($usd)
    {
        $this->_usd = $usd;
    }
}
