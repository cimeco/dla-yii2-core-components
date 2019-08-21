<?php

namespace quoma\core\modules\menu\components;


use quoma\core\modules\menu\models\MenuItem;



/**
 * Description of MenuItemFactory
 *
 * @author juan
 */
class MenuItemFactory {
    
    const ABSOLUTE_LINK_TYPE= 'abs';
    const ARTICLE_LINK_TYPE = 'art';
    const CATEGORY_LINK_TYPE = 'cat';
    const SECTION_LINK_TYPE = 'sec';
    const STATIC_PAGE_LINK_TYPE = 'sp';
    const SUB_MENU_TYPE= 'sub';
        
    /**
     * Devuelve una instancia de MenuItem. La clase de la instancia la define de acuerdo al campo class de la tabla 
     * @param type $condition
     * @return \common\modules\menu\components\class
     */
    public static function findOneInstance($condition){
        
        $item= MenuItem::findOne($condition);
        $instance = null;
        
        if (!empty($item)) {
            $instance = new $item->class;
            $instance->loadData($item);
        }

        return $instance;
    }
    
    /**
     * Devuelve un array de instancias de MenuItem. La clase de cada instancia depende del valor del campo class de la tabla
     * @param type $condition
     * @return \common\modules\menu\components\class
     */
    public static function findAllInstance($condition){
        
        $items= MenuItem::findAll($condition);
        $instances = [];
        
        if (!empty($items)) {
            foreach ($items as $item){
                $instance = new $item->class;
                $instance->loadData($item);
                $instances[]= $instance;
            }
        }

        return $instances;
    }
        
    
    
    
}
