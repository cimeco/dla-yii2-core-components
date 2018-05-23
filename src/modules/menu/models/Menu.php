<?php

namespace quoma\core\modules\menu\models;

use quoma\core\modules\menu\components\MenuItemFactory;
use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $menu_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * 
 *
 * @property MenuItem[] $menuItems
 * @property MenuLocation[] $menuLocations
 */
class Menu extends \yii\db\ActiveRecord
{
    
    public $items;
    public $_saveItems = false;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'description'], 'required'],
            [['items'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => 'Menu ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
        ];
    }

    public function behaviors() {
        return array_merge (parent::behaviors(), [
            [
                'class'=> \yii\behaviors\SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
                'immutable' => true
            ],            
        ]);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuLocations()
    {
        return $this->hasMany(MenuLocation::className(), ['menu_id' => 'menu_id']);
    }

    public function getChildren(){
        return MenuItemFactory::findAllInstance(['menu_id' => $this->menu_id, 'parent_id' => null]);
    }
    
    /**
     * Luego de guardar el registro de menu en la BD guardo los items recibidos
     * @param type $insert
     * @param type $changedAttributes
     * @return boolean
     */    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        if(!$this->saveItems()){
            return false;
        }
        $this->_saveItems = true;
        //MenuItem::preorderTree($this->menu_id);
        
        if (!$insert) {
            Yii::$app->cache->delete('menu-'.$this->slug);// solo al actualizar mato la posible cache del menu
        }
        
        return true;
    }
    
    
    /**
     * Guarda los items del menu en la base de datos
     * @return boolean
     */
    public function saveItems(){
        if (!empty($this->menuItems)) {
            foreach ($this->menuItems as $item){
                $item->delete();
            }
        }
        
        if ($this->items) {
            foreach ($this->items as $i){
                if(MenuItem::validateClass($i['class'])){
                    $i['class']::create($i, $this->menu_id);
                }else{
                    return false;
                }
            }
        }

        return true;
    }
    
    /**
     * Renderiza el menu para ser mostrado en la vista y formulario de backend
     * @param type $sub
     * @param type $parent
     * @return string
     */
    public function renderForBack($sub= false, $parent= null){
        $menu = '<ul '. (!$sub ? 'id="menu-preview"': '').' class="navbar">';
        $items = \common\modules\menu\components\MenuItemFactory::findAllInstance(['menu_id' => $this->menu_id, 'parent_id' => null]);
        $position= 1;
        if (!empty($items)) {
            foreach ($items as $item){
                $itemInfo= $item->renderForBack($position, $sub, $parent);
                $position = $itemInfo['pos'];
                $menu .= $itemInfo['item'];            
            }
        }
        
        $menu .= '</ul>';
        
        return $menu;
    }
    
    /**
     * Renderiza el menu para ser mostrado en frontend
     * @return string
     */
    public function render($sub= false){
        if (Yii::$app->cache->get('menu-'.$this->slug)) {
            return Yii::$app->cache->get('menu-'.$this->slug);
        }
        
        $menu= $sub ? '<ul class="dropdown-menu">' :'<ul>';
        $items = \common\modules\menu\components\MenuItemFactory::findAllInstance(['menu_id' => $this->menu_id, 'parent_id' => null]);
        
        foreach ($items as $key => $item) {
            $menu .= $item->renderItem(($key + 1), 0);
        }
        
        $menu .= '</ul>';
        
        Yii::$app->cache->set('menu-'. $this->slug, $menu, 86400 );
        
        return $menu;
    }

    public function beforeDelete()
    {
        parent::beforeDelete();
        if(!empty($this->menuItems)){
            foreach ($this->menuItems as $item){
                $item->delete();
            }
        }

        return true;
    }

}
