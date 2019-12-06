<?php

namespace models;

use models\services\RepositoryMysqlService;

/**
 * Class Query
 * @package models
 * @property RepositoryMysqlService $_repositoryService
 */
class Query
{
    private $_queryString;
    private $_tableName;
    private $_alias;
    private $_tables = [];
    private $_select;
    private $_repositoryService;

    public function __construct($tableName)
    {
        $this->_repositoryService = RepositoryMysqlService::getInstance();

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

    public function one()
    {
        if (current($this->_select) == '*') {

            $cols = $this->getColumnsByTable($this->_tables);

            $vals = $this->getSliceColsOneValues();

            $data = $this->uniteColsWithOneVals($cols, $vals);

        } else {

            $data =  $this->_repositoryService->getDefinedFields($this->_queryString, 'one');

        }

        return $data;
    }

    public function all()
    {
        if (current($this->_select) == '*') {
            $data = $this->_repositoryService->getAllFieldsByTables($this->_queryString, $this->_tables);
        }else{
            $data =  $this->_repositoryService->getDefinedFields($this->_queryString, 'all');
        }

        return $data;
    }



}
