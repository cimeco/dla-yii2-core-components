<?php

namespace quoma\core\helpers;

/**
 * Includes function for db data manipulation
 *
 * @author marcelo
 */
class DbHelper {
        
   public static function getDbName($db = 'db')
    {
        $dsn = Yii::$app->get($db)->dsn;
        if (preg_match('/' . 'dbname' . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

}
