<?php

namespace models;

use MongoDB\Client;

class TestMongo
{
    public static function execute()
    {
        $collection = (new Client)->test->users;

//$collection->insertOne(['name'=>'Max','year'=>30]);

        var_dump($collection->findOne(['name' => 'Max'], [
            'projection' => [
                'year' => 1,
            ]]));

    }
}
