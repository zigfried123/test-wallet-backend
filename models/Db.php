<?php

namespace models;

abstract class Db
{
    protected function init()
    {
        $this->initDb();

        $this->connect();
    }

    abstract public function initDb();

    abstract public function connect();

    abstract public function getConfig();
}
