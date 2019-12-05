<?php

namespace modules\user\controllers;

use models\Controller;
use models\Mysql;
use models\Profiler;
use modules\user\behaviors\UserBehavior;
use modules\user\models\entities\UserEntity;
use modules\user\models\repositories\UserRepository;
use modules\user\models\services\UserService;

/**
 * @property UserService $service
 * @property UserRepository $repository
 */
class UserController extends Controller
{
    public function __construct($type, $method)
    {
        parent::__construct($type, $method);
    }

    public function methods()
    {
        return [
            'POST' => ['register'],
            'GET' => ['getUserData'],
        ];
    }

    public function register(string $name,int $currency)
    {
        /**
         * @var $user UserEntity
         */

        $pdo = Mysql::$db;

        /**
         * @var \PDO $pdo
         */

        try {

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $pdo->beginTransaction();

            $user = $this->service->addUser($name);

            (new UserBehavior())->onUserSave($currency, $user->getId());

            $pdo->commit();

        }catch(\Exception $e){
            $pdo->rollBack();
            var_dump($e->getMessage());
        }

        return $user;
    }

    public function getUserData()
    {


/*
        $data = $this->repository->find()
            ->alias('u')
            ->select(['*'])
            ->leftJoin(['wallet w' => ['user_id', 'id']])
            ->leftJoin(['balance b' => ['wallet_id', 'id']])
            ->where(['user_id'=>'78'])
            ->groupBy('u.id')
            ->orderBy('u.id ASC')
            ->all();


        var_dump($data);

*/


      // $this->repository->dropProcedure('user_data'); die;

        $tables = 'wallet,balance';

/*
        $sql = "
        set @tables = 'wallet,balance';
        
SELECT *,@tables FROM `user` u LEFT JOIN wallet w ON w.user_id = u.id LEFT JOIN balance b ON b.wallet_id = u.id WHERE user_id = t GROUP BY u.id ORDER BY u.id ASC";

        $this->repository->createProcedure('user_data','IN t int',$sql); die;

*/



    // var_dump($data); die;
/*



*/

$params = ['user_id'=>83];

        Profiler::start();

var_dump($this->repository->callProcedure('user_data',array_values($params)));

        Profiler::end();

        die;


       // $this->repository->dropProcedure('user_data');

       // Mysql::$db->query($sql);


       // return $data;
    }
}
