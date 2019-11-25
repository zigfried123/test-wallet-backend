<?php

namespace models;

class AccessControl
{
    public static function checkAccess($type, $method, $methods)
    {
        if(!is_array($methods[$type]) || !in_array($method, $methods[$type])){
            throw new \Exception('access forbidden');
        }
    }

}
