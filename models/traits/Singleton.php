<?php

namespace models\traits;

trait Singleton
{
    private static $instance;

    public static function getInstance()
    {
        $class = new self;

        if(!(self::$instance instanceof $class)){
            self::$instance = $class;
        }

        return self::$instance;
    }


}
