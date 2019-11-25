<?php

namespace modules\wallet\models\strategies;

use modules\wallet\models\services\BalanceService;

class RefundStrategy
{
    public function handle($data)
    {
        extract($data);

        $service = new BalanceService();

        return $service->refundBalance($walletId, $transactionType);
    }
}
