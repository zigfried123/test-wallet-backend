<?php

namespace models;
/**
 * Class Entity
 * @package models
 * @property int $_id
 */
class Entity
{
    private $_id;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }


}
