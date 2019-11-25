<?php

namespace models;

abstract class Controller
{
    protected $service;
    protected $repository;
    protected $entity;

    public function __construct($type, $method)
    {
        $class = str_replace(['Controller','controllers'],['Repository','models\repositories'],get_class($this));
        $this->repository = new $class();

        $class = str_replace(['Controller','controllers'],['Service','models\services'],get_class($this));
        $this->service = new $class();

        $class = str_replace(['Controller','controllers'],['Entity','models\entities'],get_class($this));
        $this->entity = new $class();


        $type = strtoupper($type);

        AccessControl::checkAccess($type,$method,$this->methods());
    }

    abstract public function methods();
}
