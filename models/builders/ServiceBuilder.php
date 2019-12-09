<?php

namespace models\builders;

class ServiceBuilder implements Builder
{
    private $_service;

    public function __construct($service)
    {
        $this->_service = $service;
    }

    public function build()
    {
        $this->setEntity();
        $this->setRepository();
    }

    public function setRepository()
    {
        $class = str_replace(['Service','services'],['Repository','repositories'],get_class($this->_service));
        $this->_service->repository = new $class();
    }

    public function setEntity()
    {
        $class = str_replace(['Service','services'],['Entity','entities'],get_class($this->_service));
        $this->_service->entity = new $class();
    }
}
