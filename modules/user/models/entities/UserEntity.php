<?php

namespace modules\user\models\entities;

use models\Entity;

class UserEntity extends Entity
{
    private $_name;


    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function __call($name, $args)
    {
        if(strpos($name,'set') !== false) {
            $prop = strtolower(str_replace('set', '', $name));

            $this->$prop = $args[0];
        }
    }

}
