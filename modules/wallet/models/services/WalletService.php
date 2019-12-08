<?php

namespace modules\wallet\models\services;

use models\services\Service;
use modules\wallet\models\entities\WalletEntity;
use modules\wallet\models\repositories\BalanceRepository;
use modules\wallet\models\repositories\RateRepository;
use modules\wallet\models\repositories\WalletRepository;

/** @property WalletEntity $entity */
class WalletService extends Service
{
    private $_repository;
    private $_balanceRepository;
    private $_rateRepository;

    public function __construct()
    {
        parent::__construct();

        $this->_repository = new WalletRepository();
        $this->_balanceRepository = new BalanceRepository();
        $this->_rateRepository = new RateRepository();
    }

    public static function createName()
    {
        return mt_rand(111111, 999999);
    }

    public function checkEqualCurrencies($walletId, $currency)
    {
        $wallet = $this->_repository->getWalletById($walletId);

        return $this->isEqualCurrencies($wallet['currency'], $currency);
    }

    public function getCurrentCurrency($walletId)
    {
        $wallet = $this->_repository->getWalletById($walletId);
        return $wallet['currency'];
    }

    public function createWallet($currency, $userId)
    {
        $name = $this->createName();

        $this->entity->setName($name);

        $this->entity->setCurrency($currency);

        $this->entity->setUserId($userId);

        $id = $this->_repository->create($this->entity);

        return [$id, $name];
    }


    private function isEqualCurrencies($from, $to)
    {
        return $from == $to;
    }
}
