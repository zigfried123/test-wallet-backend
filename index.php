<?php

use models\App;
use models\Mysql;
use models\Test;
use models\TestMongo;
use models\TestMongoMysql;
use models\TestSql;
use models\MongoDb;
use modules\user\behaviors\UserBehavior;
use modules\user\controllers\UserController;

require_once './vendor/autoload.php';

(new Mysql)->init('test_wallet');
(new MongoDb)->init('test');

//TestMongo::execute();


//TestSql::testForeignKey();



//Test::testMethod();


//(new UserController('GET','getUserData'))->getUserData();

TestMongoMysql::test();

//App::run($argv);



