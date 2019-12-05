<?php

namespace models;

use MongoDB\Client;

class MongoDb extends Db
{
    public static $db;

    public function init($dbName='')
    {
        $this->connect();
        $this->initDb($dbName);
    }

    public function initDb($dbName='')
    {
        self::$db = self::$db->$dbName;
    }

    public function connect()
    {
        self::$db = (new Client());
    }

    public function getConfig()
    {
        return require './config/mongoDb.php';
    }

}
