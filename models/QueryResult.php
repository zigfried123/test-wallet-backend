<?php

namespace models;

use models\services\RepositoryMysqlService;

class QueryResult
{
    public function getResult($select, $queryString, $tables)
    {
        $service = new RepositoryMysqlService();

        if($select == '*'){
            return $service->getAllFieldsByTables($queryString, $tables);
        }else{
            return $service->getDefinedFields($queryString);
        }
    }
}
