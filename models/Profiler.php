<?php

namespace models;

class Profiler
{
    private static $_startMemory;
    private static $_startTime;

    public static function start()
    {
        self::$_startMemory = memory_get_usage();
        self::$_startTime = microtime(true);
    }

    public static function end()
    {
        printf('Время: %s',microtime(true) - self::$_startTime);

        echo PHP_EOL;

        printf('Память: %s',memory_get_usage() - self::$_startMemory);

        echo PHP_EOL;
    }
}
