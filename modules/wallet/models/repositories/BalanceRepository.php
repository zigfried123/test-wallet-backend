<?php

namespace modules\wallet\models\repositories;

use models\Repository;

class BalanceRepository extends Repository
{
    public function addSum($sum, $walletId)
    {
        $sql = "INSERT INTO {$this->tableName} (sum,wallet_id,date_created) VALUES (:sum,:wallet_id,:date)";
        $this->execute($sql, [':sum' => $sum, ':wallet_id' => $walletId, ':date' => time()]);
    }

    public function getLastSum($walletId)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE wallet_id=:id ";
        return $this->findSql($sql, [':id' => $walletId])['sum'];
    }


    public function findWeekAgoSum($walletId)
    {
        $weekAgo = strtotime("-1 week");

        $sql = "SELECT sum FROM {$this->tableName} WHERE wallet_id=:wallet_id AND date_created<:weekAgo ORDER BY date_created DESC";
        $res = $this->findSql($sql, [':wallet_id'=>$walletId,':weekAgo'=>$weekAgo]);

        return $res['sum'];
    }
}
