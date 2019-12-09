<?php

namespace models;

class QueryOne extends QueryResult
{
    public function getResult($select, $queryString, $tables)
    {
        return parent::getResult($select, $queryString, $tables);
    }
}
