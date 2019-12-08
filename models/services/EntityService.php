<?php

namespace models\services;

use models\Entity;

class EntityService
{
    public function getEntity($tableName)
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

    public function fillEntity($data, $entityTable)
    {
        $entity = $this->getEntity($entityTable);

        $values = array_values($data);
        $keys = $this->normalizeKeys(array_keys($data));

        $data = array_combine($keys,$values);

        foreach ($data as $key => $val) {
            $entity->{'set' . ucfirst($key)}($val);
        }

        return $entity;
    }

    // replace uppercase letter to low line
    protected function normalizeKeys($keys)
    {
        $keys = array_map(function($v){
           $v = explode('_',$v);
           $v = array_map('ucfirst', $v);
           $v = implode('',$v);

            return $v;
        }, $keys);

        return $keys;
    }

    private function addDinamicFields($entity, $data)
    {
        return array_merge(get_object_vars($entity),$data);
    }

    public function getProperties($entity)
    {
        if(!($entity instanceof Entity)) {
            return ['_id'=>$entity];
        }

        $methods = get_class_methods($entity);

        $data = [];

        foreach ($methods as $method) {

            if (strpos($method, 'get') !== false) {

                $prop = lcfirst(str_replace('get', '', $method));

                $data[$prop] = $entity->$method();
            }
        }

        $data = $this->addDinamicFields($entity, $data);

        return $data;
    }

}
