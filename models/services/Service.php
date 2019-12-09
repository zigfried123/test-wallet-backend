<?php

namespace models\services;

use models\traits\NestedSingleton;

class Service
{
    use NestedSingleton;

    public $repository;
    public $entity;

    public function __construct()
    {

    }
}
