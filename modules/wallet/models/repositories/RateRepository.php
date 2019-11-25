<?php

namespace modules\wallet\models\repositories;

use models\Repository;

class RateRepository extends Repository
{
    public function getLastRate()
    {
        $sql = "SELECT * FROM rate ORDER BY id DESC";
        $data = $this->findSql($sql);
        return $data;
    }

}
