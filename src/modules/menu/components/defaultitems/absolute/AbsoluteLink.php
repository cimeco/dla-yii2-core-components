<?php

namespace quoma\core\modules\menu\models\items;

use quoma\core\modules\menu\models\MenuItem;
use Yii;
use yii\web\JsExpression;

/**
 * Description of AbsoluteLink
 *
 * @author juan
 */
class AbsoluteLink extends MenuItem {

    public static $form = '@menu/components/defaultitems/absolute/views/_absolute_link.php';


    public static function typeName(){
        return Yii::t('app','Absolute Link');
    }

    public function getClassLabel(){
        return self::typeName();
    }

    public static function hasChildren(){
        return false;
    }



}
