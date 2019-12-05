<?php

namespace models;

class Migration
{
    public function createTable($table, $cols)
    {
        $values = implode(', ', array_values($cols));

        return Mysql::$db->query("CREATE TABLE `$table` ($values)");
    }

    public function dropTable($table)
    {
        Mysql::$db->query("TRUNCATE TABLE `$table`");
        return Mysql::$db->query("DROP TABLE `$table`");
    }

    public function getTinyInt($col, $unique=null)
    {
        return "$col tinyint unsigned not null default 0 $unique";
    }

    public function getInt($col, $unique=null)
    {
        return "$col int unsigned not null default 0 $unique";
    }

    public function getPrimaryKey($col)
    {
        return "$col int NOT NULL AUTO_INCREMENT PRIMARY KEY";
    }

    public function getVarChar($col,$length,$unique=null)
    {
        return "$col varchar($length) not null default '' $unique";
    }

    public function getFloat($col, $length = null)
    {
        return isset($length) ? "$col float($length) not null default 0" : "$col float not null default 0";
    }

    public function getDouble($col, $length = null)
    {
        return isset($length) ? "$col float($length) not null default 0" : "$col float not null default 0";
    }
}
