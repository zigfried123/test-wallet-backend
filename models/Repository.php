<?php

namespace models;

use models\builders\RepositoryBuilder;

abstract class Repository
{
    public $tableName;
    public $entityService;

    public function __construct()
    {
        $builder = new RepositoryBuilder($this);
        $builder->build();
    }

    abstract public function findOne($data);

}
