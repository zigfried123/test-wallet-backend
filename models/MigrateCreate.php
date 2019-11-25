<?php

namespace models;

class MigrateCreate
{
    private $_name;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function execute()
    {
        $migrateName = 'm' . mt_rand(100000, 999999) . '_' . mt_rand(100000, 999999) . '_' . $this->_name;

        $fileData = "<?php
        
    namespace migrations;
        
    use models\Migration;
    
    class $migrateName extends Migration
    {
    
        public function up(){
        
        }
    
        public function down(){
        
        }
    
    }
    
    ";
        mkdir("./migrations");

        file_put_contents("./migrations/$migrateName.php", $fileData);
    }
}
