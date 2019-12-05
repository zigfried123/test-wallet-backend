<?php
        
    namespace migrations;
        
    use models\Migration;
    
    class m965479_263932_add_rate_table extends Migration
    {
    
        public function up(){
            return $this->createTable('rate', ['id' => $this->getPrimaryKey('id'), 'rub' => $this->getDouble('rub'), 'usd' => $this->getDouble('usd')]);
        }
    
        public function down(){;
            return $this->dropTable('rate');
        }
    
    }
    
