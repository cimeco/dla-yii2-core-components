<?php

namespace quoma\core\modules\menu\models;

use quoma\core\helpers\ClassFinderHelper;
use quoma\core\modules\menu\components\MenuItemFactory;
use quoma\core\modules\menu\MenuModule;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu_item".
 *
 * @property integer $menu_item_id
 * @property string $label
 * @property string $url
 * @property string $class
 * @property integer $left
 * @property integer $rigth
 * @property integer $menu_id
 * @property integer $parent_id
 * @property string target
 *
 * @property Menu $menu
 * @property MenuItem $parent
 * @property MenuItem[] $menuItems
 */
class MenuItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'url', 'class',  'menu_id'], 'required'],
            [['left', 'rigth', 'target'], 'safe'],
            [['left', 'rigth', 'menu_id', 'parent_id'], 'integer'],
            [['label', 'target'], 'string', 'max' => 45],
            [['url', 'class'], 'string', 'max' => 255],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'menu_id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItem::className(), 'targetAttribute' => ['parent_id' => 'menu_item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_item_id' => 'Menu Item ID',
            'label' => 'Label',
            'url' => 'Url',
            'class' => 'Class',
            'left' => 'Left',
            'rigth' => 'Rigth',
            'menu_id' => 'Menu ID',
            'parent_id' => 'Parent ID',
            'target' => 'Target',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['menu_id' => 'menu_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MenuItem::className(), ['menu_item_id' => 'parent_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['parent_id' => 'menu_item_id']);
    }
       
    public function getChildren(){
        
        return MenuItemFactory::findAllInstance(['parent_id' => $this->menu_item_id]);
    }
    public function loadData($item){
        $this->menu_item_id = $item->menu_item_id;
        $this->label = $item->label;
        $this->url = $item->url;
        $this->class = $item->class;
        $this->left = $item->left;
        $this->rigth = $item->rigth;
        $this->parent_id = $item->parent_id;
        $this->menu_id = $item->menu_id;
        $this->target= $item->target;
    }
    
    public static function getTypes(){
        
        $items_paths = array_merge(MenuModule::getInstance()->defaultItemsPath, MenuModule::getInstance()->itemsPaths);
        \Yii::info($items_paths);
        $classes= ClassFinderHelper::findClasses($items_paths, true, true, [MenuItem::className()]);
        $types= [];
        \Yii::info($classes);
        foreach ($classes as $class){

            $types[] = [
                'class' => $class,
                'view' => $class::$form,
                'name' => $class::typeName(),
                'child' => $class::canBeChild(),
                'children' => $class::hasChildren(),
            ];

        }

        return $types;
    }
    
     /**
     * Funcion para armar preorder (Modified Preorder Tree Traversal)
     */
    public static function preorderTree($menu_id, $nodes = null, $lft = 1)
    {
        
        if($nodes === null){
            $nodes = self::find()->where('parent_id IS NULL')->andWhere(['menu_id' => $menu_id])->orderBy(['menu_item_id' => SORT_ASC])->all();
        }
        
        $rgt= $lft + 1;
        
        error_log(print_r($nodes,1));
        
        
        foreach($nodes as $node){
            if($node->getChildren()->exists()){
                $children= $node->getChildren()->all();
                $rgt = self::preorderTree($children, $rgt);
            }else{
                $rgt = $lft + 1;
            }
            
            $node->updateAttributes([
                'left' => $lft,
                'rigth' => $rgt
            ]);
            $lft = $rgt+1;
        }
        
        return $rgt+1;
        
    }
    
    /**
     * Antes de eliminar borro las relaciones con los hijos
     * @return boolean
     */
    public function beforeDelete() {
        parent::beforeDelete();
        if(!empty($this->menuItems)){
            foreach ($this->menuItems as $item){
                $item->delete();
            }
        }
        
        return true;
    }
    
    /**
     * Valida si la clase del item es valida
     * @param type $class
     * @return boolean
     */
    public static function validateClass($class){
                
        if (class_exists($class)) {
            return true;
        }else{
            return false;
        }
    }


    public static function create($item, $menu_id, $parent = null){
        $instance= new self();

        $instance->label= $item['label'];
        $instance->class= self::className();
        $instance->url= $item['url'];
        $instance->parent_id = $parent;
        $instance->menu_id= $menu_id;
        $instance->target= $item['target'];

        if ($instance->save(false)) {
            if (isset($item['children'])) {
                foreach ($item['children'] as $i){
                    if(MenuItem::validateClass($i['class'])){
                        $i['class']::create($i, $menu_id, $instance->menu_item_id);
                    }
                }
            }
            return true;
        }else{
            error_log(print_r($instance->getErrors()),1);
            return false;
        }


    }

    public static function canBeChild(){
        return true;
    }

    /**
     * Renderiza el item para el frontend
     * @return string
     */
    public function renderItem($position, $parent_pos= ''){
        $target= $this->target !== null ? ' target="'.$this->target.'"' : '';
        if(count($this->children) > 0){

            $item= '<li class="dropdown">'
                . '<a class ="dropdown-toggle" href="'.$this->createUrl().'"'
                . ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"'. $target .'>'.$this->label.'</a>';

            $item .= '<ul class="dropdown-menu dropdown-menu-right">';
            foreach ($this->children as $key => $child){
                $item.= $child->renderItem(($key + 1), $position);
            }
            $item.= '</ul>';
        }else{
            $item= '<li><a href="'.$this->createUrl().'"'. $target. '>'.$this->label.'</a>';

        }

        $item.= '</li>';

        return $item;
    }

    public function createUrl(){
        return $this->url;
    }

    public function getClassLabel(){
        return static::typeName();
    }



}
