<?php

namespace models;

class App
{
    public static function run($argv=[])
    {
        if (count($argv) > 1) {
            Console::getInstance($argv)->execute();
        } else {
            try {
                echo (new Routing())->execute();
            }catch(\Exception $e){
                echo $e->getMessage();
            }
        }
    }
}
