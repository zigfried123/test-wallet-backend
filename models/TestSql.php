<?php

namespace models;

use modules\user\models\repositories\UserRepository;

class TestSql
{
    public static function testIndexes()
    {

        ini_set('wait_timeout','28800');

        ini_set('max_allowed_packet','434558800');

/*
       $this->fillTestTable();
*/


      //  self::prepareQueries();

    }

    private static function prepareQueries()
    {
        $join = 'left join city c on c.id=test.city';

        $where = "age=8 and city<>0 and gender<>''";

        $q = self::explain('test',$where,$join);

        $q = self::select('test',$where,$join);
    }


    private static function fillTestTable()
    {
        $data = self::getRandomUsers();

        self::insert('test',$data['cols'],$data['vals']);
    }

    private static function explain($table, $where, $join='')
    {
        $q = Mysql::$db->query("explain SELECT * FROM $table $join where $where");
        var_dump($q->fetch());
    }


    private static function select($table, $where, $join='')
    {
        $start = microtime(true);

        $q = Mysql::$db->query("SELECT * FROM $table where $where");

        var_dump($q->fetch());

        $end = microtime(true) - $start;

        echo $end;

        return $q;
    }


    private function clearTable($table)
    {
        Mysql::$db->query("TRUNCATE TABLE $table");
    }

    private static function insert($table,$cols=[],$data=[])
    {


        $vals = implode(',', $data);

        $cols = array_map(function($v){
            return "`$v`";
        }, $cols);

        $cols = '('.implode(',',$cols).')';

        $sql = "INSERT INTO $table $cols VALUES $vals";

        Mysql::$db->query($sql);
    }


    private static function getRandomRates()
    {
        $rates = [];

        while(1) {

            $usd = mt_rand(1, 970);
            $rub = round(1, 800);

            $rates[] = "($usd, $rub)";

            if(count($rates) >= 1000000) break;

        }

        return $rates;
    }



    private static function getRandomName()
    {
        $letters = [];

        for ($i = ord('a'); $i <= ord('z'); $i++){
            $letters[] = chr($i);
        }

        $word = [];

        while(1) {

            $randomLetter = $letters[mt_rand(0, count($letters))];

            $word[] = $randomLetter;

            if(count($word) == 6) break;

        }

        $name = implode('',$word);

        return $name;
    }

    private static function getRandomCity()
    {
        $cities = [1,2,3,4,5];

        return self::getRandomData($cities);
    }

    private static function getRandomData($data=[])
    {
        return $data[mt_rand(0,count($data))];
    }


    private static function getRandomGender()
    {

        $genders = ['male','female'];

        return self::getRandomData($genders);

    }

    private static function getRandomUsers($data=[], $count=1200000)
    {
        while(1) {

            $name = self::getRandomName();

            $city = self::getRandomCity();

            $gender = self::getRandomGender();

            $age = mt_rand(0, 99);

            $cols = compact('name','city','gender','age');

            extract($cols);

            if(isset($name, $city, $age, $gender)) {
                $data[] = "('$name', '$city', '$gender', '$age')";
            }


            if(count($data) >= $count) break;

        }

        $data['vals'] = $data;

        $data['cols'] = array_keys($cols);

        return $data;
    }

    public static function dropProcedure($name='test')
    {
        $sql = "
        DROP PROCEDURE $name
       ";

        Mysql::$db->query($sql);
    }

    public static function testProcedure($name='test')
    {
        self::dropProcedure();

        $sql = "
        CREATE PROCEDURE $name(in t1 int, out t3 int)
      BEGIN
         SELECT sum into t3 FROM user where sum=t1 limit 1;
       END;
        
         ";

        $q = Mysql::$db->query($sql);

      //  var_dump($q->fetch());

        self::initProcedure();
    }

    public static function initProcedure($name='test')
    {
        $sql = "
        CALL test(500,@a);
        ";

        $q = Mysql::$db->query($sql);

        $sql = "select @a;";

         $q = Mysql::$db->query($sql);

        var_dump($q->fetch());
    }

    public static function testTrigger()
    {
        $sql = "
        CREATE TRIGGER `test` AFTER INSERT ON `user`
        FOR EACH ROW 
        BEGIN
           set @a=1;
            INSERT INTO `wallet` (name,user_id,currency) VALUES (123,NEW.id,@a);
        END;
        ";

        $q = Mysql::$db->query($sql);
    }

    public static function dropTrigger()
    {
        $sql = 'DROP TRIGGER test';
        Mysql::$db->query($sql);
    }

    public static function testForeignKey()
    {

        $sql = 'SET FOREIGN_KEY_CHECKS=0';

        $q = Mysql::$db->query($sql);


        $sql = '
ALTER TABLE wallet
ADD CONSTRAINT FK_User
FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE;
';

        $q = Mysql::$db->query($sql);

        $sql = 'SET FOREIGN_KEY_CHECKS=1';
        $q = Mysql::$db->query($sql);
    }

    public static function testView()
    {
        $sql = "CREATE VIEW max as SELECT * FROM user WHERE name='max'";
        Mysql::$db->query($sql);

        $sql = "SELECT * FROM max";

        $q = Mysql::$db->query($sql);

        var_dump($q->fetchAll());
    }

}
