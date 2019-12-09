<?php

namespace models;

class QueryAll  extends QueryResult
{

    public function getResult($select, $queryString, $tables)
    {
        return parent::getResult($select, $queryString, $tables);
    }

}
