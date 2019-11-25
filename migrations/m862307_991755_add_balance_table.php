<?php
        
    namespace migrations;
        
    use models\Migration;
    
    class m862307_991755_add_balance_table extends Migration
    {
    
        public function up(){
            return $this->createTable('balance', ['id' => $this->getPrimaryKey('id'), 'sum' => $this->getFloat('sum',null,'NOT NULL'), 'wallet_id' => $this->getInt('wallet_id'), 'date_created' => $this->getInt('date_created'), 'transaction_type' => $this->getTinyInt('transaction_type'), 'reason' => $this->getTinyInt('reason')]);
        }
    
        public function down(){
            return $this->dropTable('balance');
        }
    
    }
    
