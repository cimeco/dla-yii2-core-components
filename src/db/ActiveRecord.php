<?php

namespace quoma\core\db;

/**
 * Description of ActiveRecord
 *
 * @author mmoyano
 */
class ActiveRecord extends \yii\db\ActiveRecord{
    
    public function getDeletable(){
        
        return false;
        
    }
    
}
