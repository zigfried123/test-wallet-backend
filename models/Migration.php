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

    public function getTinyInt($col)
    {
        return "$col tinyint";
    }

    public function getInt($col)
    {
        return "$col int";
    }

    public function getPrimaryKey($col)
    {
        return "$col int NOT NULL AUTO_INCREMENT PRIMARY KEY";
    }

    public function getVarChar($col, $length = null)
    {
        return isset($length) ? "$col varchar($length)" : "$col varchar";
    }

    public function getFloat($col, $length = null, $default=NULL)
    {
        return isset($length) ? "$col float($length) $default" : "$col float $default";
    }
}
