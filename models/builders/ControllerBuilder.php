<?php

namespace models\builders;

use models\Controller;

class ControllerBuilder implements Builder
{
    private $_controller;

    public function __construct(Controller $controller)
    {
        $this->_controller = $controller;
    }

    public function build()
    {
        $this->setService();
        $this->setRepository();
        $this->setEntity();
    }

    public function setService()
    {
        $class = str_replace(['Controller','controllers'],['Service','models\services'],get_class($this->_controller));
        $this->_controller->service = new $class();
    }

    public function setRepository()
    {
        $class = str_replace(['Controller','controllers'],['Repository','models\repositories'],get_class($this->_controller));
        $this->_controller->repository = new $class();
    }

    public function setEntity()
    {
        $class = str_replace(['Controller','controllers'],['Entity','models\entities'],get_class($this->_controller));
        $this->_controller->entity = new $class();
    }
}
