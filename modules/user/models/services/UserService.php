<?php

namespace modules\user\models\services;

use models\Service;
use modules\wallet\models\services\BalanceService;
use modules\wallet\models\services\WalletService;

class UserService extends Service
{
    private $_walletService;
    private $_balanceService;

    public function __construct()
    {
        parent::__construct();
        $this->_walletService = new WalletService();
        $this->_balanceService = new BalanceService();
    }

    public function createWallet($currency, $userId)
    {
        list($wallet, $name) = $this->_walletService->createWallet($currency, $userId);

        $this->_balanceService->initStartBalance($wallet->getId());

        return json_encode(['id' => $wallet->getId(), 'name' => $name, 'currency' => $currency]);
    }

    public function addUser($name)
    {
        $this->entity->setName($name);

        $id = $this->repository->create($this->entity);

        return $id;
    }

}
