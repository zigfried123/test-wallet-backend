<?php

namespace models\services;

use models\builders\ServiceBuilder;
use models\traits\NestedSingleton;

class Service
{
    use NestedSingleton;

    public $repository;
    public $entity;

    public function __construct()
    {
        $builder = new ServiceBuilder($this);
        $builder->build();
    }
}
