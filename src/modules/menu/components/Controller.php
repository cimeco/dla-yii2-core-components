<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 24/05/18
 * Time: 12:19
 */

namespace quoma\core\modules\menu\components;


use quoma\core\modules\menu\MenuModule;
use quoma\core\web\Controller as SuperController;

class Controller extends SuperController
{
    public function behaviors()
    {

        if (MenuModule::getInstance()->use_user_module == false){
            return [];
        }

        return array_merge(parent::behaviors(), MenuModule::getInstance()->extraBehaviors);
    }
}