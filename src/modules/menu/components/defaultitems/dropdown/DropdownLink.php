<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 15/05/18
 * Time: 14:17
 */

namespace quoma\core\modules\menu\components\defaultitems\dropdown;


use quoma\core\modules\menu\models\MenuItem;
use Yii;

class DropdownLink extends MenuItem
{
    public static $form = '@menu/components/defaultitems/dropdown/views/_dropdown_link.php';

    public static function typeName(){
        return Yii::t('app','Dropdown');
    }

    public function getUrl(){
        return '#';
    }

    public static function canBeChild(){
        return false;
    }

    public function getClassLabel(){
        return self::typeName();
    }

    public static function hasChildren(){
        return true;
    }

}