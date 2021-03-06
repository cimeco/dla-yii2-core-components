<?php

namespace quoma\core\modules\menu\models;

use quoma\core\modules\menu\MenuModule;
use Yii;

/**
 * This is the model class for table "menu_location".
 *
 * @property integer $menu_location_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property integer $menu_id
 * @property integer $site_id
 *
 * @property Menu $menu
 */
class MenuLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules= [
            [['name', 'description'], 'required', 'on' => 'default'],
            [['name', 'description', 'slug'], 'required', 'on' => 'front-save'],
            [['menu_id', 'site_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 55],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'menu_id']],
        ];

        if (MenuModule::getInstance() && MenuModule::getInstance()->multisite){
            $rules[]= [['site_id'], 'required'];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_location_id' => 'Menu Location ID',
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'slug' => Yii::t('app','Slug'),
            'menu_id' => Yii::t('app','Menu'),
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

        if (!empty(MenuModule::getInstance()) && MenuModule::getInstance()->multisite){
            $sluggable_config['ensureUnique']= false;
        }

        if ($this->scenario === 'front-save'){
            return parent::behaviors();
        }
        return array_merge (parent::behaviors(), [
            $sluggable_config,

        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['menu_id' => 'menu_id']);
    }
}
