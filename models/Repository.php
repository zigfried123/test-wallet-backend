<?php

namespace models;

abstract class Repository
{
    protected $tableName;
    protected $entity;
    protected $entities;

    public function __construct()
    {
        $classNameWithoutPostfix = str_replace('Repository', '', get_class($this));

        $classNameWithoutPostfix = explode('\\', $classNameWithoutPostfix);

        $classNameWithoutNamespace = end($classNameWithoutPostfix);

        $this->tableName = lcfirst($classNameWithoutNamespace);

        $this->initEntity();

    }

    private function initEntity()
    {
        $entity = str_replace(['Repository', 'repositories'], ['Entity', 'entities'], get_class($this));

        $this->entity = new $entity();
    }

    abstract public function findOne($data);

    protected function fillEntity($data)
    {
        $this->initEntity();

        foreach ($data as $key => $val) {
            $this->entity->{'set' . ucfirst($key)}($val);
        }

        return $this->entity;

    }

    protected function fillEntities(\PDOStatement $q)
    {
        $data = $q->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $i=>$indexArr){
            foreach($indexArr as $field=>$val) {

                $method = 'set' . ucfirst($field);
                if(method_exists($this->entity,$method)) {
                    $this->entity->$method($val);
                }

            }

            $this->entities[$i] =  $this->entity;

        }

        return $this->entities;

    }

    private function addDinamicFields($entity, $data)
    {
        return array_merge(get_object_vars($entity),$data);
    }

    protected function getProperties($entity)
    {
        if(!($entity instanceof Entity)) {
            return ['_id'=>$entity];
        }

        $methods = get_class_methods($entity);

        $data = [];

        foreach ($methods as $method) {

            if (strpos($method, 'get') !== false) {

                if ($method=='getEntity' || $entity->$method() == null) continue;

                $prop = lcfirst(str_replace('get', '', $method));

                $data[$prop] = $entity->$method();
            }
        }

        $data = $this->addDinamicFields($entity, $data);

        return $data;
    }

    // replace uppercase letter to low line
    protected function normalizeKeys($keys)
    {
        $keys = array_map(function($v){
            $v = preg_split('/(?=[A-Z])/',$v);
            $v = implode('_', $v);
            $v = strtolower($v);
            return $v;
        }, $keys);

        return $keys;
    }
}
