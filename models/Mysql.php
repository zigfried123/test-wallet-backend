<?php

namespace models;

/**
 * Class Mysql
 * @package models
 */
class Mysql extends Db
{
    private static $_db;

    public function init($dbName='')
    {
        $this->initDb($dbName);
        $this->connect($dbName);
        $this->initMigrations();
    }

    public function initDb($dbName='')
    {
        $config = self::getConfig();

        extract($config);

        $db = new \PDO("mysql:host=$server", $user, $password);

       return $db->exec("CREATE DATABASE `$dbName`;
            CREATE USER '$user'@'localhost' IDENTIFIED BY '$password';
            GRANT ALL ON `$dbName`.* TO '$user'@'localhost';
            FLUSH PRIVILEGES;")
        or false;

    }

    public function connect($dbName='')
    {
        $db = self::getConfig();

        extract($db);

        $pdo = new \PDO("mysql:host=$server;dbname=$dbName", $user, $password);

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        if(!(self::$_db instanceof \PDO)) {
            self::$_db = $pdo;
        }

    }

    public static function getDb():\PDO
    {
        return self::$_db;
    }

    private function initMigrations()
    {
        try {
            self::getDb()->query("SELECT * FROM `migrations` LIMIT 1");
        } catch (\Exception $e) {
            self::getDb()->query('CREATE TABLE `migrations` (`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255))');
        }
    }

    public function getConfig()
    {
        return require './config/mysql.php';
    }
}
