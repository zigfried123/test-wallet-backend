<?php

namespace modules\wallet\models\repositories;

use models\RepositoryMysql;

class RateRepository extends RepositoryMysql
{
    public function getLastRate()
    {
        $sql = "SELECT * FROM rate ORDER BY id DESC";
        $data = $this->findSql($sql);
        return $data;
    }

}
