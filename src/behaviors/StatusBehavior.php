<?php

namespace quoma\core\behaviors;

use Yii;
use yii\db\ActiveRecord;

/**
 * Description of StatusBehaviour
 *
 * @author martin
 */
class StatusBehavior extends \yii\base\Behavior
{
    
    const STATUS_DELETED = 0;
    const STATUS_DISABLED = 5;
    const STATUS_ENABLED = 10;
    const STATUS_DRAFT = 20;
    const STATUS_PENDING = 30;

    /**
     * Define los estados posibles:
     *  basic = enabled y disabled
     *  full = {default} + draft + deleted + pending
     * @var type 
     */
    public $config = 'basic';
    
    public $deleted = false;
    
    /**
     * Si se deben capturar el evento EVENT_BEFORE_VALIDATE para validar el estado
     */
    public $captureEvents = true;
    
    public function events()
    {
        if($this->captureEvents == false){
            return [];
        }
        
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }
    
    /**
     * Devuelve la lista de estados, indexado por estado y con valor igual
     * a etiqueta de estado.
     * @return array
     */
    public function getStatusList()
    {
        $basic = [
            self::STATUS_ENABLED => Yii::t('app', 'Enabled'),
            self::STATUS_DISABLED => Yii::t('app', 'Disabled'),
        ];
        
        if($this->deleted == true){
            $basic[self::STATUS_DELETED] = Yii::t('app', 'Deleted');
        }
        
        if($this->config == 'basic'){
            return $basic;
        }
        
        if($this->config == 'full'){
            return $basic + [
                self::STATUS_DRAFT => Yii::t('app', 'Draft'),
                self::STATUS_PENDING => Yii::t('app', 'Pending'),
            ];
        }
    }
    
    /**
     * Devuelve la lista de estados, al igual que la funcion anterior. Se la declara static para los casos en los que no tengo un objeto
     * y nesecito el listado de estados
     * @param type $type
     * @param type $deleted
     * @return type
     */
    public static function statusList($type= 'basic', $deleted= true)
    {
        $basic = [
            self::STATUS_ENABLED => Yii::t('app', 'Enabled'),
            self::STATUS_DISABLED => Yii::t('app', 'Disabled'),
        ];
        
        if($deleted == true){
            $basic[self::STATUS_DELETED] = Yii::t('app', 'Deleted');
        }
        
        if($type == 'basic'){
            return $basic;
        }
        
        if($type == 'full'){
            return $basic + [
                self::STATUS_DRAFT => Yii::t('app', 'Draft'),
                self::STATUS_PENDING => Yii::t('app', 'Pending'),
            ];
        }
    }
    
    /**
     * Devuelve el nombre del estado actual
     * @return string
     */
    public function getStatusName()
    {
        
        $list = $this->getStatusList();
        
        if(isset($list[$this->owner->status])){
            return $list[$this->owner->status];
        }
        
        return null;
        
    }
    
    /**
     * Devuelve rango valido de estados
     * @return array
     */
    public function getStatusRange()
    {
        $list = $this->getStatusList();
        return array_keys($list);
    }
    
    /**
     * Validamos estado. TODO: validar transiciones
     */
    public function beforeValidate()
    {
        $range = $this->getStatusRange();

        if(in_array($this->owner->status, $range)){
            return true;
        }
        
        if(!$this->owner->isAttributeRequired('status') && empty($this->owner->status) ){
            return true;
        }

        $this->owner->addError('status', Yii::t('yii', '{attribute} is not in the allowed range.', ['attribute' => Yii::t('app', 'Status')]));
        return false;
    }
    
}
