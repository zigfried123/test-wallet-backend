<?php

namespace models;

class MigrateDown
{
    public function execute()
    {
        $q = Mysql::getDb()->query('SELECT * FROM `migrations` ORDER BY id DESC LIMIT 1');
        $mgrs = $q->fetch()['name'];


        $class = '\migrations\\' . $mgrs;

        if (class_exists($class)) {
            if ((new $class())->down()) {
                $q = Mysql::getDb()->prepare("DELETE FROM `migrations` WHERE name=:migration");
                if($q->execute(['migration' => $mgrs])) echo "миграция $mgrs отменена";
            }
        }

    }
}
