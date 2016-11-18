<?php

namespace quoma\core\helpers;

/**
 * Includes function for db data manipulation
 *
 * @author marcelo
 */
class DbHelper {
    
    /**
     * return database name of a connection
     * 
     * @param type $connection
     * @return string
     */

    public static function getDbName($connection) {
        $dsn = $connection->dsn;
        if (preg_match('/' . 'dbname' . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

}
