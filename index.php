<?php

use models\App;
use models\Mysql;
use models\tests\Test;
use models\tests\TestMongo;
use models\tests\TestMongoMysql;
use models\tests\TestSql;
use models\MongoDb;
use modules\user\controllers\UserController;
use modules\user\models\repositories\UserRepository;

require_once './vendor/autoload.php';

(new Mysql)->init('test_wallet');
(new MongoDb)->init('test');

//new UserRepository(); die;

(new UserController('GET','getUserData'))->getUserData();

//App::run($argv);



