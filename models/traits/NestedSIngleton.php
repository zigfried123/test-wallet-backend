<?php

namespace models\traits;

trait NestedSingleton
{
    protected static $instance;

    public static function getInstance():static
    {
        $class = new static;

        if(!(static::$instance instanceof $class)){
            static::$instance = $class;
        }

        return static::$instance;
    }


}
