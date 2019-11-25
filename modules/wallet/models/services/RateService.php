<?php

namespace modules\wallet\models\services;

use models\Service;
use modules\wallet\models\entities\RateEntity;
use modules\wallet\models\entities\WalletEntity;
use modules\wallet\models\repositories\RateRepository;

/**
 * Class RateService
 * @package models\services
 * @property RateRepository $repository
 * @property RateEntity $entity
 */
class RateService extends Service
{
    public function isNotEqualCurrencyStrategy($currency, $sum)
    {
        $rate = $this->repository->getLastRate();

        if (!$rate) {

            throw new \Exception('not rates');

        }

        $currencyName = strtolower(WalletEntity::CURRENCIES[$currency]);

        $sum = $rate[$currencyName] * $sum;

        return $sum;

    }

    public function addRates($usd, $rur)
    {
        $this->entity->setId(null);
        $this->entity->setUsd($usd);
        $this->entity->setRub($rur);

        $this->repository->create($this->entity);
    }
}
