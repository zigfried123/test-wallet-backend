<?php

namespace models;

/**
 * Class Query
 * @package models
 * @property RepositoryMysql $_repository
 */
class Query
{
    private $_queryString;
    private $_tableName;
    private $_alias;
    private $_tables = [];
    private $_select;
    private $_repository;

    public function __construct($tableName, $repository)
    {
        $this->_repository = $repository;

        $this->_tables[] = $tableName;

        $this->_tableName = $tableName;

        $q = "SELECT * FROM `{$tableName}` {$tableName}";

        $this->_queryString = $q;

        return $this;

    }

    public function leftJoin($relation, callable $callback = null)
    {
        list($table, $alias) = explode(' ', key($relation));

        $this->_tables[] = $table;

        $on = current($relation);
        $this->_queryString .= " LEFT JOIN $table $alias ON $alias.$on[0] = {$this->_alias}.$on[1]";

        if ($callback) {
            $this->_queryString = $callback($this)->_queryString;
        }

        return $this;
    }

    public function orderBy($orderBy)
    {
        $this->_queryString .= " ORDER BY $orderBy";
        return $this;
    }

    public function groupBy($groupBy)
    {
        $this->_queryString .= " GROUP BY $groupBy";
        return $this;
    }

    public function where($condition)
    {
        $key = key($condition);
        $val = current($condition);
        $this->_queryString .= " WHERE $key = $val";

        return $this;
    }

    public function alias($alias)
    {
        $this->_alias = $alias;
        return $this;
    }

    public function select($select = [])
    {
        $this->_select = $select;

        $fields = array_map(function ($v) {
            if (strpos($v, '.id') !== false) {
                $v2 = str_replace('.', '_', $v);
                $v = "$v as $v2";
            }
            return $v;
        }, $select);

        $fields = implode(',', $fields);

        $q = "SELECT $fields FROM `{$this->_tableName}` {$this->_alias}";

        $this->_queryString = $q;

        return $this;
    }

    public function getRawSql()
    {
        return $this->_queryString;
    }

    public function getColumnsByTable($tables)
    {
        $output = [];

        foreach ($tables as $table) {

            $q = Mysql::$db->prepare("SHOW COLUMNS FROM $table");

            $q->execute();

            $cols = $q->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($cols as $col) {
                $output[$table][] = $col['Field'];
            }
        }

        return $output;

    }

    private function getSliceColsOneValues()
    {

        $q = Mysql::$db->query($this->_queryString);

        $res = $q->fetch(\PDO::FETCH_NUM);

        $i = 0;

        $cols = [];

        $counts = $this->countColsInTables();

        foreach ($counts as $col => $count) {

            $cols[$col] = array_slice($res, $i, $count);

            $i += $count;

        }

        return $cols;
    }


    private function uniteColsWithOneVals($cols, $vals)
    {
        $data = [];

        foreach ($cols as $table => $col) {
            $data[$table] = array_combine($col, $vals[$table]);
        }

        return $data;
    }

    public function one()
    {
        if (current($this->_select) == '*') {

            $cols = $this->getColumnsByTable($this->_tables);

            $vals = $this->getSliceColsOneValues();

            $data = $this->uniteColsWithOneVals($cols, $vals);

        } else {

          $data = $this->fetch(\PDO::FETCH_ASSOC);

        }

        return $data;
    }

    private function fetch($fetchStyle)
    {
        $q = Mysql::$db->query($this->_queryString);

        $data = $q->fetch(\PDO::FETCH_ASSOC);

        return $data;
    }

    private function fetchAll($fetchStyle)
    {
        $q = Mysql::$db->query($this->_queryString);

        $data = $q->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    public function all()
    {

        if (current($this->_select) == '*') {

            $this->_repository->setTables($this->_tables);

            $data = $this->_repository->getAllFields($this->_queryString);

        }else{
            $data =  $this->_repository->getDefinedFields();
        }

        return $data;
    }



}
