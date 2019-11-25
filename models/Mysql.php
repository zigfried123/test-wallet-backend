<?php

namespace models;

class Mysql
{
    /** @var \PDO $db */
    public static $db;

    public static function initDb()
    {
        $config = self::getConfig();

        extract($config);

        $db = new \PDO("mysql:host=$server", $user, $password);

       return $db->exec("CREATE DATABASE `$database`;
            CREATE USER '$user'@'localhost' IDENTIFIED BY '$password';
            GRANT ALL ON `$database`.* TO '$user'@'localhost';
            FLUSH PRIVILEGES;")
        or false;

    }

    public static function connect()
    {
        $db = self::getConfig();

        extract($db);

        self::$db = new \PDO("mysql:host=$server;dbname=$database", $user, $password);

        self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    }

    public static function initMigrations()
    {
        try {
            Mysql::$db->query("SELECT * FROM `migrations` LIMIT 1");
        } catch (\Exception $e) {
            Mysql::$db->query('CREATE TABLE `migrations` (`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255))');
        }
    }

    private static function getConfig()
    {
        return require './config/mysql.php';
    }
}
