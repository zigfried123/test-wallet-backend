<?php

namespace modules\wallet\models\strategies;

class Reason
{
    const REASONS = [1 => 'stock', 2 => 'refund'];

    private $_strategy;

    public function setStrategy($reasonId)
    {
        switch(self::REASONS[$reasonId]){
            case 'stock':
                $this->_strategy = new StockStrategy();
                break;
            case 'refund':
                $this->_strategy = new RefundStrategy();
                break;
        }
    }

    public function handle($data)
    {
        return $this->_strategy->handle($data);
    }
}
