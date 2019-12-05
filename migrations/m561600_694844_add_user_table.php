<?php
        
    namespace migrations;
        
    use models\Migration;
    
    class m561600_694844_add_user_table extends Migration
    {
    
        public function up(){
            return $this->createTable('user', ['id' => $this->getPrimaryKey('id'),'name' => $this->getVarChar('name',10, 'unique')]);
        }
    
        public function down(){
            return $this->dropTable('user');
        }
    
    }
    
