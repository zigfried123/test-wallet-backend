<?php

namespace modules\wallet\observers;

use models\traits\Singleton;
use modules\wallet\models\services\WalletService;

class WalletObserver
{
    use Singleton;

    public function onUserSave($currency, $userId)
    {
        $walletService = WalletService::getInstance();

        $walletService->createWallet($currency, $userId);
    }
}
