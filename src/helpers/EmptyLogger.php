<?php
/**
 * Created by PhpStorm.
 * User: cgarcia
 * Date: 22/12/15
 * Time: 10:34
 */


namespace quoma\core\helpers;

use yii\log\Logger;

class EmptyLogger extends Logger
{
    public function log($message, $level, $category = 'application')
    {
        return false;
    }
}