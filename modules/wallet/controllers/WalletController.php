<?php

namespace modules\wallet\controllers;

use models\Controller;

use models\Entity;
use modules\wallet\models\entities\WalletEntity;
use modules\wallet\models\repositories\BalanceRepository;
use modules\wallet\models\repositories\WalletRepository;
use modules\wallet\models\services\BalanceService;
use modules\wallet\models\services\RateService;
use modules\wallet\models\services\WalletService;
use modules\wallet\models\strategies\Reason;


/**
 * Class WalletController
 * @package controllers
 * @property WalletRepository $repository
 * @property WalletService $service
 */
class WalletController extends Controller
{
    private $_balanceRepository;
    private $_rateService;
    private $_balanceService;

    public function __construct($type, $method)
    {
        parent::__construct($type, $method);

        $this->_balanceRepository = new BalanceRepository();
        $this->_rateService = new RateService();
        $this->_balanceService = new BalanceService();
    }

    public function methods()
    {
        return [
            'POST' => ['changeBalance'],
            'GET' => ['getBalance','getLastWalletId'],
        ];
    }

    public function changeBalance($walletId,$sum,$currency,$reasonId,$transactionType)
    {
        $reason = new Reason();

        $reason->setStrategy($reasonId);

        $currentCurrency = $this->service->getCurrentCurrency($walletId);

        $data = $reason->handle(compact('walletId','currency','sum','transactionType', 'reasonId'));

        if($data instanceof Entity){
            $sum = $data->getSum();
        }else{
            $sum = $data;
        }

        return ['balance'=>$sum.' '.WalletEntity::CURRENCIES[$currentCurrency]];
    }

    public function getBalance($walletId)
    {
        $balance = $this->repository->getBalance($walletId);

        return ['balance'=>$balance];
    }

    public function getLastWalletId()
    {
        return $this->repository->getLastWalletId();
    }

}
