<?php

namespace models\traits;

trait Singleton
{
    private static $instance;

    public static function getInstance():self
    {
        $class = new self;

        if(!(self::$instance instanceof $class)){
            self::$instance = $class;
        }

        return self::$instance;
    }


}
