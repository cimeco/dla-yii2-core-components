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
        $dsn = \Yii::$app->get($db)->dsn;
        if (preg_match('/' . 'dbname' . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

    public static function getDbHost($db = 'db')
    {
        $dsn = Yii::$app->get($db)->dsn;
        if (preg_match('/' . 'host' . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

    public static function getDbPort($db = 'db')
    {
        $dsn = Yii::$app->get($db)->dsn;
        if (preg_match('/' . 'port' . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

    public static function getDbUsername($db = 'db')
    {
        return Yii::$app->get($db)->username;
    }

    public static function getDbPassword($db = 'db')
    {
        return Yii::$app->get($db)->password;
    }

}
