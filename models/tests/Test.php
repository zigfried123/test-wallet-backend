<?php

namespace models\tests;

use modules\user\controllers\UserController;
use modules\user\models\entities\UserEntity;
use modules\user\models\repositories\UserRepository;
use MongoDB\BSON\Regex;

class Test
{
        public static function testMethod()
        {
            /*
            $memoryStart = memory_get_usage();

            echo json_encode((new UserController('GET','getUserData'))->getUserData());


            $memoryEnd = memory_get_usage() - $memoryStart;

            echo 'Потребляемая память: '.$memoryEnd;

            die;
            */


            $entity = new UserEntity();

            $entity->setName('Max');


            $to = new UserEntity();

            $to ->setName('Alex');


            $entities = [$entity,$to];

            $id = '5de68f671f11000097002c22';

            $r = (new UserRepository())->deleteMany($entity);

            var_dump($r);

        }
}
