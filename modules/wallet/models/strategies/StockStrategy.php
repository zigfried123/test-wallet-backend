<?php

namespace modules\wallet\models\strategies;

use modules\wallet\models\services\BalanceService;
use modules\wallet\models\services\RateService;
use modules\wallet\models\services\WalletService;

class StockStrategy
{
    public function handle($data)
    {
        extract($data);

        $walletService = new WalletService();
        $rateService = new RateService();
        $balanceService = new BalanceService();

        $isEqual = $walletService->checkEqualCurrencies($walletId, $currency);

        if(!$isEqual) {
            $sum = $rateService->isNotEqualCurrencyStrategy($currency, $sum);
        }

        return $balanceService->addSum($sum, $walletId, $reasonId, $transactionType);
    }
}
