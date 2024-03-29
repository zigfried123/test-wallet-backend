<?php

namespace models\traits;

trait NestedSingleton
{
    protected static $instance;

    public static function getInstance()
    {
        $class = new static;

        if(!(static::$instance instanceof $class)){
            static::$instance = $class;
        }

        return static::$instance;
    }


}
