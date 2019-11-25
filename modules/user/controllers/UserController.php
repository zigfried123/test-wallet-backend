<?php

namespace modules\user\controllers;

use models\Controller;
use modules\user\models\services\UserService;
use modules\wallet\models\services\BalanceService;
use modules\wallet\models\services\WalletService;

/** @property UserService $service */
class UserController extends Controller
{
    private $_walletService;
    private $_balanceService;

    public function __construct($type, $method)
    {
        parent::__construct($type, $method);
        $this->_walletService = new WalletService();
        $this->_balanceService = new BalanceService();
    }

    public function methods()
    {
        return [
            'POST' => ['register'],
        ];
    }

    public function register($data)
    {
        $user = $this->service->addUser($data['name']);

        $this->service->createWallet($data['currency'], $user->getId());

        return $user;
    }
}
