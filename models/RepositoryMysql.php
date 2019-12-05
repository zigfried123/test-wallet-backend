<?php

namespace models;

/**
 * Class Repository
 * @package models
 */
class RepositoryMysql extends Repository
{
    private $_db;
    private $_tables;

   public function __construct()
   {
       parent::__construct();

       $this->_db = Mysql::$db;

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

        $q = $this->_db->prepare($sql);

        /**
         * @var $entity Entity
         */

        if ($q->execute($properties)) {

            $id = $this->_db->lastInsertId();

            $entity->setId($id);

            return $entity;

        }

        return false;
    }

    public function find()
    {
        return new Query($this->tableName, $this);
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

    private function execute($sql, $params)
    {
        $q = $this->_db->prepare($sql);

        $q->execute($params);

        return $q;
    }

    private function getKeyValueParamsFromArray($data)
    {
        foreach($data as $key=>$val){
            $params[] = "$key='$val'";
        }

        $params = implode(' AND ', $params);

        return $params;

    }

    public function getAllFields($queryString)
    {

        $cols = $this->getColumnsByTable();

        $vals = $this->getSliceColsAllValues($queryString);

        $data = $this->uniteColsWithAllVals($cols, $vals);

        return $data;
    }

    private function uniteColsWithAllVals($cols, $dataTables)
    {
        $dataTables = array_map(function ($tables) use ($cols) {

            $names = $keys = array_keys($tables);

            $tablesWithoutNames = array_map(function ($vals, $table) use ($cols) {

                return array_combine($cols[$table], $vals);
            }, $tables, $keys);

            $tables = array_combine($names, $tablesWithoutNames);

            return $tables;

        }, $dataTables);

        return $dataTables;
    }

    private function countColsInTables()
    {
        $cols = $this->getColumnsByTable();

        return array_map(function ($v) {

            $v = count($v);

            return $v;

        }, $cols);
    }

    private function getSliceColsAllValues($queryString)
    {
        $q = Mysql::$db->query($queryString);

        $rows = $q->fetchAll(\PDO::FETCH_NUM);

        $q->closeCursor();

        $counts = $this->countColsInTables();

        $arrays = [];

        foreach ($rows as $values) {

            $i = 0;

            $tables = [];

            foreach ($counts as $table => $count) {

                $tables[$table] = array_slice($values, $i, $count);

                $i += $count;

            }

            $arrays[] = $tables;

        }

        return $arrays;
    }



    public function getDefinedFields()
    {
        $data = $this->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    public function dropProcedure($name)
    {
        $sql = "DROP PROCEDURE $name";

        Mysql::$db->query($sql);
    }

    public function callProcedure($name, $params)
    {
        $params = implode(',',$params);

        $sql = "CALL $name($params)";

        $q = Mysql::$db->query($sql);

        $data = $q->fetchAll(\PDO::FETCH_NUM);

        $q->closeCursor();

        $this->_tables = explode(',',end(end($data)));

        $data = $this->getAllFields($sql);

        return $data;

    }

    public function setTables($tables)
    {
        $this->_tables = $tables;
    }


    public function getColumnsByTable()
    {
        $data = [];

        /**
         * @var \PDOStatement $q
         */

        foreach ($this->_tables as $table) {

            $q = Mysql::$db->prepare("SHOW COLUMNS FROM $table");

            $q->execute();

            $cols = $q->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($cols as $col) {
                $data[$table][] = $col['Field'];
            }
        }

        return $data;

    }





    public function createProcedure($name, $params, $sql)
    {
        $sql = "CREATE PROCEDURE $name($params)
        
        BEGIN
            $sql;
        END;
        
        ";

        Mysql::$db->query($sql);
    }


}
