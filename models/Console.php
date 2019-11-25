<?php

namespace models;

class Console
{
    public static function getInstance($argv)
    {
        switch ($argv[1]) {
            case 'migrate/create':
                return new MigrateCreate($argv[2]);
            case 'migrate/up':
                return new MigrateUp();
            case 'migrate/down':
                return new MigrateDown();
        }
    }
}
