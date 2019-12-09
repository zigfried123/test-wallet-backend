<?php

namespace models;

use models\builders\ControllerBuilder;

abstract class Controller
{
    public $service;
    public $repository;
    public $entity;

    public function __construct($type, $method)
    {
        $bulder = new ControllerBuilder($this);

        $bulder->build();

        $type = strtoupper($type);

        AccessControl::checkAccess($type,$method,$this->methods());
    }

    abstract public function methods();

}
