<?php
        
    namespace migrations;
        
    use models\Migration;
    
    class m271012_481856_ extends Migration
    {
    
        public function up(){
            return $this->createTable('wallet', ['id' => $this->getPrimaryKey('id'), 'currency' => $this->getTinyInt('currency'), 'name' => $this->getInt('name'), 'user_id' => $this->getInt('user_id')]);
        }
    
        public function down(){
            return $this->dropTable('wallet');
        }
    
    }
