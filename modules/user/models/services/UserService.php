<?php

namespace modules\user\models\services;

use models\Service;
use modules\user\models\entities\UserEntity;
use modules\user\models\repositories\UserRepository;
use modules\wallet\models\entities\WalletEntity;
use modules\wallet\models\services\BalanceService;
use modules\wallet\models\services\WalletService;

/**
 * Class UserService
 * @package modules\user\models\services
 * @property UserEntity $entity
 * @property UserRepository $repository
 */
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
        /**
         * @var $wallet WalletEntity
         */

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
