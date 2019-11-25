<?php

namespace models;

class Entity
{

    public static function getEntity($tableName)
    {
        $tableName = ucfirst($tableName);
        $class = "models\\entities\\$tableName".'Entity';
        return new $class();
    }

}
