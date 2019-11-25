<?php

namespace models;

class Service
{
    protected $repository;
    protected $entity;

    public function __construct()
    {
        $class = str_replace(['Service','services'],['Repository','repositories'],get_class($this));
        $this->repository = new $class();
        $class = str_replace(['Service','services'],['Entity','entities'],get_class($this));
        $this->entity = new $class();
    }
}
