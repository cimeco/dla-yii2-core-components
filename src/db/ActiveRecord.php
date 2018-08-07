<?php

namespace quoma\core\db;

/**
 * Description of ActiveRecord
 *
 * @author mmoyano
 */
class ActiveRecord extends \yii\db\ActiveRecord {

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
        
    /**
     * Devuelve la lista de errores en forma concatenada
     *
     * @return string
     */
    public function getErrorsAsString()
    {
        $result = '';
        foreach ($this->getErrors() as $attribute => $messages) {
            foreach ($messages as $message) {
                $attributeLabel = $this->getAttributeLabel($attribute);
                if (!empty($result)) {
                    $result .= ' | ';
                }
                $result .= $attributeLabel . ': ' . $message;
            }
        }
        return $result;
    }
    
    /**
     * Devuelve una lista de attributos de tipo date o datetime:
     * 
     *  [
     *      'date_attr' => 'date',
     *      'other_date_attr' => 'date',
     *      'datetime_attr' => 'datetime'
     *  ]
     *  
     * @return type
     */
    public function getDateAttributes()
    {
        return [];
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert)){
            foreach($this->getDateAttributes() as $dateAttr => $type){
                $formatter = \quoma\core\helpers\DateFormatter::getInstance();
                
                if($type == 'date'){
                    $this->$dateAttr = $formatter->dbDateFormat($this->$dateAttr);
                }else{
                    $this->$dateAttr = $formatter->dbDatetimeFormat($this->$dateAttr);
                }
            }
            return true;
        }
        return false;
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        foreach($this->getDateAttributes() as $dateAttr => $type){
            $formatter = \quoma\core\helpers\DateFormatter::getInstance();

            if($type == 'date'){
                $this->$dateAttr = $formatter->hDateFormat($this->$dateAttr);
            }else{
                $this->$dateAttr = $formatter->hDatetimeFormat($this->$dateAttr);
            }
        }
    }
    
    public function afterFind() {
        parent::afterFind();
        
        foreach($this->getDateAttributes() as $dateAttr => $type){
            $formatter = \quoma\core\helpers\DateFormatter::getInstance();

            try{
                if($type == 'date'){
                    $this->$dateAttr = $formatter->hDateFormat($this->$dateAttr);
                }else{
                    $this->$dateAttr = $formatter->hDatetimeFormat($this->$dateAttr);
                }
            }catch(\Throwable $e){
            }
        }
    }
}
