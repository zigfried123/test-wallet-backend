<?php

namespace modules\wallet\models\repositories;

use models\Repository;
use modules\wallet\models\entities\WalletEntity;

/**
 * Class WalletRepository
 * @package repositories
 *
 */
class WalletRepository extends Repository
{

    public function getWalletById($walletId)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id=:id";

        $data = $this->findSql($sql, [':id' => $walletId]);

        return $data;
    }

    public function getBalance($walletId)
    {
        $sql = "SELECT currency,b.sum as sum FROM {$this->tableName} w LEFT JOIN balance b ON b.wallet_id = w.id WHERE w.id=:wallet_id ORDER BY b.id DESC";

        $data = $this->findSql($sql, [':wallet_id' => $walletId]);

        $balance = sprintf('%s %s', $data['sum'], WalletEntity::CURRENCIES[$data['currency']]);

        return $balance;
    }

    public function getLastWalletId()
    {
        $sql = "SELECT * FROM {$this->tableName} ORDER BY id DESC";

        $wallet = $this->findSql($sql);

        return $wallet['id'];
    }





}
