<?php

namespace models;

use models\services\RepositoryMysqlService;

/**
 * Class Repository
 * @package models
 * @property \PDO $_db
 * @property RepositoryMysqlService $_repositoryMysqlService
 */
class RepositoryMysql extends Repository
{
    private $_db;
    private $_repositoryMysqlService;

   public function __construct()
   {
       parent::__construct();

       $this->_db = Mysql::$db;

       $this->_repositoryMysqlService = RepositoryMysqlService::getInstance();

   }

    public function create(Entity $entity)
    {
        $properties = $this->getProperties($entity);

        $keys = $this->normalizeKeys(array_keys($properties));

        $keys = implode(',', $keys);

        $vals = implode(',', array_map(function ($v) {
            return ":$v";
        }, array_keys($properties)));

        $sql = "INSERT INTO `{$this->tableName}` ($keys) VALUES ($vals)";

        if ($this->execute($sql, $properties)) {

            $id = $this->_db->lastInsertId();

            $entity->setId($id);

            return $entity;

        }

        return false;
    }

    public function find()
    {
        return new Query($this->tableName);
    }

    public function findOne($data)
    {
        if(is_integer($data)) {
            $id = $data;
            $sql = "SELECT * FROM `{$this->tableName}` WHERE id=:id";
            $params = [':id' => $id];
        }elseif(is_array($data)){
            $sql = "SELECT * FROM `{$this->tableName}` WHERE";
            $sql .= " ".$this->getKeyValueParamsFromArray($data);
            $params = $data;
        }

        $q = $this->execute($sql, $params);

        $data = $q->fetch(\PDO::FETCH_ASSOC);

        $this->fillEntity($data);

        return $this->entity;
    }

    public function findSql($sql, $params=[])
    {
        $q = $this->execute($sql, $params);

        $data = $q->fetch(\PDO::FETCH_ASSOC);

        $this->fillEntity($data);

        return $data;
    }

    protected function execute($sql, $params): \PDOStatement
    {
        $q = $this->_db->prepare($sql);

        $q->execute($params);

        return $q;
    }

    public function dropProcedure($name)
    {
        $sql = "DROP PROCEDURE $name";

        $this->_db->query($sql);
    }

    public function callProcedure($name, $params)
    {
        $params = implode(',',$params);

        $sql = "CALL $name($params)";

        $q = $this->_db->query($sql);

        $data = $q->fetch(\PDO::FETCH_NUM);

        $q->closeCursor();

        $tables = explode(',',end($data));

        $data = $this->_repositoryMysqlService->getAllFieldsByTablesAllResults($sql,$tables);

        var_dump($data); die;

        return $data;

    }

    public function createProcedure($name, $params, $sql)
    {
        $sql = "CREATE PROCEDURE $name($params)
        
        BEGIN
            $sql;
        END;
        
        ";

        $this->_db->query($sql);
    }


}
