<?php

namespace quoma\core\db;

/**
 * Description of ActiveRecord
 *
 * @author mmoyano
 */
class ActiveRecord extends \yii\db\ActiveRecord{

    /**
     * Carga dinamicamente los behaviors en base a los configurados en params.
     * Para poder cargarlos en params tiene que tener un parametro de tipo array con la siguiente estructura:
     *
     * 'ClaseBehaviors' => [
     *          'namespace\\clase\\behavior'
     * ]
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if( array_key_exists(get_class($this).'Behaviors', \Yii::$app->params)) {
            $behaviors = array_merge($behaviors, \Yii::$app->params[get_class($this).'Behaviors']);
        }

        return $behaviors;
    }

    public function getDeletable(){
        
        return false;
        
    }
    
}
