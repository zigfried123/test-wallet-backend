<?php

use models\Console;
use models\Mysql;
use models\Routing;

require_once './vendor/autoload.php';


Mysql::initDb();

Mysql::connect();

Mysql::initMigrations();


if (count($argv) > 1) {
    Console::getInstance($argv)->execute();
} else {
    try {
        echo (new Routing())->execute();
    }catch(Exception $e){
        echo $e->getMessage();
    }
}


