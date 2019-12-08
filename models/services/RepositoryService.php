<?php

namespace models\services;

class RepositoryService
{
    // replace uppercase letter to low line
    public static function normalizeKeysFromVars($keys)
    {
        $keys = array_map(function ($v) {
            $v = preg_split('/(?=[A-Z])/', $v);
            $v = implode('_', $v);
            $v = strtolower($v);
            return $v;
        }, $keys);

        return $keys;
    }
}
