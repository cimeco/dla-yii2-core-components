<?php

namespace quoma\core\modules\menu\models;

use quoma\core\db\ActiveRecord;
use quoma\core\modules\menu\components\MenuItemFactory;
use quoma\core\modules\menu\MenuModule;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "menu".
 *
 * @property integer $menu_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property integer $site_id
 * 
 *
 * @property MenuItem[] $menuItems
 * @property MenuLocation[] $menuLocations
 */
class Menu extends ActiveRecord
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
            [['name', 'description'], 'required'],
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
            'menu_id' => Yii::t('app','ID'),
            'name' => Yii::t('app','Name'),
            'slug' => Yii::t('app','Slug'),
            'description' => Yii::t('app','Description'),
        ];
    }

    public function behaviors() {

        $sluggable_config= [
            'class'=> \yii\behaviors\SluggableBehavior::className(),
            'attribute' => 'name',
            'slugAttribute' => 'slug',
            'ensureUnique' => true,
            'immutable' => true
        ];

        if (MenuModule::getInstance() && MenuModule::getInstance()->multisite){
            $sluggable_config['ensureUnique']= false;
        }

        return array_merge (parent::behaviors(), [
            $sluggable_config,

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
        
        if (!$insert && Yii::$app->cache) {
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
     * @deprecated
     * 
     * Renderiza el menu para ser mostrado en frontend
     * @return string
     */
    public function render($sub= false, $menu_class= null, $item_class= null, $anchor_class= null, $child_class= null){
        if (MenuModule::getInstance() && MenuModule::getInstance()->multisite){
            $cache_key= 'menu-'.$this->slug. '-'.$this->site_id;
        }else{
            $cache_key= 'menu-'.$this->slug;
        }

        if (Yii::$app->cache->get($cache_key)) {
            return Yii::$app->cache->get($cache_key);
        }
        
        $menu= $sub ? '<ul class="dropdown-menu">' :'<ul class="'.$menu_class.'">';
        $items = \quoma\core\modules\menu\components\MenuItemFactory::findAllInstance(['menu_id' => $this->menu_id, 'parent_id' => null]);
        
        foreach ($items as $key => $item) {
            $menu .= $item->renderItem(($key + 1), 0, $item_class, $anchor_class, $child_class);
        }
        
        $menu .= '</ul>';

        if (Yii::$app->cache){
            Yii::$app->cache->set($cache_key, $menu, 60);
        }

        return $menu;
    }

    /**
     * @return array
     */
    public function getItemsAsArray()
    {
        $items = [];

        //TODO: modificar esto:
        $menuItems = \quoma\core\modules\menu\components\MenuItemFactory::findAllInstance(['menu_id' => $this->menu_id, 'parent_id' => null]);

        foreach($menuItems as $item){
            $children = $item->children;
            $item = [
                'label' => $item->label,
                'url' => $children ? '#' : $item->createUrl(),
            ];

            if($children){
                $item['items'] = array_map(function ($child){
                    return [
                        'label' => $child->label,
                        'url' => $child->createUrl()
                    ];
                }, $children);
            }

            $items[] = $item;
        }

        return $items;
    }
    
    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
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
