<?php

namespace models\builders;

use models\Repository;
use models\services\EntityService;

class RepositoryBuilder implements Builder
{
    private $_repository;

    public function __construct(Repository $repository)
    {
        $this->_repository = $repository;
    }

    public function setTableName()
    {
        $classNameWithoutPostfix = str_replace('Repository', '', get_class($this->_repository));

        $classNameWithoutPostfix = explode('\\', $classNameWithoutPostfix);

        $classNameWithoutNamespace = end($classNameWithoutPostfix);

        $this->_repository->tableName = lcfirst($classNameWithoutNamespace);
    }

    public function setEntityService()
    {
        $this->_repository->entityService = new EntityService();
    }

    public function build()
    {
        $this->setTableName();
        $this->setEntityService();
    }
}
