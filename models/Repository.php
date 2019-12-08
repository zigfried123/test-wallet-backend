<?php

namespace models;

use models\services\EntityService;

abstract class Repository
{
    protected $tableName;
    protected $entity;
    protected $entities;
    protected $_entityService;

    public function __construct()
    {
        $classNameWithoutPostfix = str_replace('Repository', '', get_class($this));

        $classNameWithoutPostfix = explode('\\', $classNameWithoutPostfix);

        $classNameWithoutNamespace = end($classNameWithoutPostfix);

        $this->tableName = lcfirst($classNameWithoutNamespace);

        $this->_entityService = new EntityService();

    }

    abstract public function findOne($data);

}
