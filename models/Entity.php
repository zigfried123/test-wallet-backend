<?php

namespace models;
/**
 * Class Entity
 * @package models
 * @property int $_id
 */
class Entity
{
    private $_id;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public static function getEntity($tableName)
    {
        $tableName = ucfirst($tableName);
        $modules = scandir('modules');

        foreach($modules as $module) {
            $class = "modules\\$module\\models\\entities\\$tableName" . 'Entity';
            if(class_exists($class)){
                return new $class();
            }
        }

    }

}
