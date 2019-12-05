<?php

namespace models;

use models\MongoDb;
use MongoDB\Collection;

class TestMongoMysql
{
    public static function test()
    {

        //MongoDb::$db->test->drop();

       //self::disabledCacheQueries();


        //var_dump($q->fetch());


        /*

        while($res = $q->fetch(\PDO::FETCH_ASSOC)) {

            $collection = MongoDb::$db->test->insertOne($res);

        }

        */

       // self::executeSqlAndMongoQuery();


        self::dropColumn();

        self::testVarchar();


    }

    private static function executeSqlAndMongoQuery()
    {
        /**
         * @var Collection $collection
         */

        $collection = MongoDb::$db->test;

        Profiler::start();
        $sql = "SELECT * FROM test where name='vkxcal'";
        $q = Mysql::$db->query($sql);
        Profiler::end();

        Profiler::start();
        $cursor = $collection->find(['name'=>'vkxcal']);
        Profiler::end();
    }

    private static function disabledCacheQueries()
    {
        $sql = 'SET GLOBAL query_cache_size=0;';
        Mysql::$db->query($sql);
    }

    private static function dropColumn()
    {
        $sql = 'ALTER TABLE test DROP COLUMN test';

        Mysql::$db->query($sql);
    }

    //7.2s 8s 7.4s
    private static function testInteger()
    {
        $sql = 'ALTER TABLE test ADD column test integer not null default 0';

        Mysql::$db->query($sql);
    }

    // 6.8s
    private static function testTinyint()
    {
        $sql = 'ALTER TABLE test ADD column test tinyint not null default 0';

        Mysql::$db->query($sql);
    }

    private static function testVarchar()
    {
        $sql = "ALTER TABLE test ADD column test varchar(50) not null default ''";

        Mysql::$db->query($sql);
    }

}
