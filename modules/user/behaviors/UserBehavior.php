<?php

namespace modules\user\behaviors;

use modules\wallet\models\services\WalletService;

class UserBehavior
{
    public function onUserSave($currency, $userId)
    {
        $walletService = WalletService::getInstance();

        $walletService->createWallet($currency, $userId);
    }
}
