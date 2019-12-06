<?php

namespace models\services;

use models\Mysql;
use models\traits\Singleton;

class RepositoryMysqlService
{
    use Singleton;

    private $_tables;

    public function getAllFieldsByTables($queryString,$tables)
    {
        $cols = $this->getColumnsByTable($tables);

        $vals = $this->getSliceColsAllValues($queryString, $tables);

        $data = $this->uniteColsWithAllVals($cols, $vals);

        return $data;
    }

    public function getDefinedFields($queryString, $numRows)
    {
        if($numRows == 'all') {
            $data = $this->fetchAll(\PDO::FETCH_ASSOC, $queryString);
        }elseif($numRows == 'one'){
            $data = $this->fetch(\PDO::FETCH_ASSOC, $queryString);
        }

        return $data;
    }

    public function getColumnsByTable($tables)
    {
        $data = [];

        /**
         * @var \PDOStatement $q
         */

        foreach ($tables as $table) {

            $q = Mysql::getDb()->prepare("SHOW COLUMNS FROM $table");

            $q->execute();

            $cols = $q->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($cols as $col) {
                $data[$table][] = $col['Field'];
            }
        }

        return $data;

    }

    private function fetch($fetchStyle, $queryString)
    {
        $q = Mysql::getDb()->query($queryString);

        $data = $q->fetch(\PDO::FETCH_ASSOC);

        return $data;
    }

    private function fetchAll($fetchStyle, $queryString)
    {
        $q = Mysql::getDb()->query($queryString);

        $data = $q->fetchAll($fetchStyle);

        return $data;
    }

    private function getKeyValueParamsFromArray($data)
    {
        foreach($data as $key=>$val){
            $params[] = "$key='$val'";
        }

        $params = implode(' AND ', $params);

        return $params;

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

    private function countColsInTables($tables)
    {
        $cols = $this->getColumnsByTable($tables);

        return array_map(function ($v) {

            $v = count($v);

            return $v;

        }, $cols);
    }

    private function getSliceColsAllValues($queryString, $tables)
    {
        $q = Mysql::getDb()->query($queryString);

        $rows = $q->fetchAll(\PDO::FETCH_NUM);

        $q->closeCursor();

        $counts = $this->countColsInTables($tables);

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

    private function getSliceColsOneValues($queryString)
    {

        $q = Mysql::getDb()->query($queryString);

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



}
