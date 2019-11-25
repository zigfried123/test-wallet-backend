<?php

namespace models;

use models\entities\WalletEntity;
use models\repositories\BalanceRepository;

abstract class Repository
{
    protected $_db;
    protected $tableName;
    protected $balanceRepository;
    private $_queryString;
    private $_entity;
    private $_entities;
    private $_query;
    private $_relationTable;

    public function __construct()
    {
        $this->_db = Mysql::$db;

        $classNameWithoutPostfix = str_replace('Repository', '', get_class($this));

        $classNameWithoutPostfix = explode('\\', $classNameWithoutPostfix);

        $classNameWithoutNamespace = end($classNameWithoutPostfix);

        $this->tableName = lcfirst($classNameWithoutNamespace);

        $entity = str_replace(['Repository', 'repositories'], ['Entity', 'entities'], get_class($this));

        $this->_entity = new $entity();

    }

    public function create(Entity $entity)
    {

        $methods = get_class_methods($entity);

        $data = [];


        foreach ($methods as $method) {


            if (strpos($method, 'get') !== false) {

                if ($method=='getEntity' || $entity->$method() == null) continue;

                $data[lcfirst(str_replace('get', '', $method))] = $entity->$method();
            }
        }



        $keys = array_map(function($v){
            $v = preg_split('/(?=[A-Z])/',$v);
            $v = implode('_', $v);
            $v = strtolower($v);
            return $v;
        }, array_keys($data));

        $keys = implode(',', $keys);

        $vals = implode(',', array_map(function ($v) {
            return ":$v";
        }, array_keys($data)));



        $sql = "INSERT INTO `{$this->tableName}` ($keys) VALUES ($vals)";

        $q = $this->_db->prepare($sql);



        if ($q->execute($data)) {

            $id = $this->_db->lastInsertId();

            $entity->setId($id);

            return $entity;

        }

        return false;
    }

    public function findOne($id)
    {
        $q = Mysql::$db->prepare("SELECT * FROM `{$this->tableName}` WHERE id=:id");
        $q->execute(['id' => $id]);

        $this->fillEntity($q);

        return $this->_entity;
    }

    private function fillEntity(\PDOStatement $q)
    {
        $data = $q->fetch(\PDO::FETCH_ASSOC);

        foreach ($data as $key => $val) {
            $this->_entity->{'set' . ucfirst($key)}($val);
        }

    }

    public function find($order = 'id DESC')
    {
        $q = Mysql::$db->prepare("SELECT * FROM `{$this->tableName}`");

        $this->_queryString = $q->queryString;

        return $this;
    }



    private function fillEntities(\PDOStatement $q)
    {
        $data = $q->fetchAll(\PDO::FETCH_ASSOC);

        foreach($data as $i=>$indexArr){
            foreach($indexArr as $field=>$val) {

                $method = 'set' . ucfirst($field);
                if(method_exists($this->_entity,$method)) {
                    $this->_entity->$method($val);
                }

            }

            $this->_entities[$i] =  $this->_entity;

        }

        return $this->_entities;

    }


    public function all()
    {
        $q = Mysql::$db->prepare($this->_queryString);
        $q->execute();

        $this->fillEntities($q);

        return $this->_entities;
    }

    public function execute($sql, $params)
    {
        $q = $this->_db->prepare($sql);

        $q->execute($params);
    }

    public function findSql($sql, $params=[])
    {
        $q = $this->_db->prepare($sql);

        $q->execute($params);

        $data = $q->fetch(\PDO::FETCH_ASSOC);


        return $data;
    }

}
