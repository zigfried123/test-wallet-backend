<?php

namespace modules\wallet\models\services;

use models\Service;
use modules\wallet\models\entities\BalanceEntity;
use modules\wallet\models\repositories\BalanceRepository;

/**
 * Class BalanceService
 * @package models\services
 * @property BalanceEntity $entity
 * @property BalanceRepository $repository
 */
class BalanceService extends Service
{
    public function initStartBalance($walletId)
    {
       $this->addSum(0, $walletId);
    }

    public function addSum($sum, $walletId, $reason=1, $transactionType=1)
    {
        $this->entity->setSum($sum);
        $this->entity->setWalletId($walletId);
        $this->entity->setDateCreated(time());


        $this->entity->setReason($reason);

        $this->entity->setTransactionType($transactionType);


        return $this->repository->create($this->entity);
    }

    public function refundBalance($walletId, $transactionType)
    {
        $sum = $this->repository->findWeekAgoSum($walletId);

        if(!$sum) throw new \Exception('refund is not possible');

        return $this->addSum($sum, $walletId, 2, $transactionType);
    }

}
