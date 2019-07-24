<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 23/07/19
 * Time: 11:49
 */

namespace quoma\core\db;

use webvimark\modules\UserManagement\components\AuthHelper;
use webvimark\modules\UserManagement\models\rbacDB\AbstractItem;
use webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup;
use webvimark\modules\UserManagement\models\rbacDB\Route;
use yii\db\Migration;
use webvimark\modules\UserManagement\models\rbacDB\Permission;
use yii\db\Query;
use yii\helpers\ArrayHelper;

abstract class WebvimarkAuthMigration extends Migration
{
    public function safeUp()
    {

        $allRoutes = AuthHelper::getRoutes();

        foreach ($this->permissions() as $groupData){
            $group = $this->saveGroup($groupData);
            foreach($groupData['permissions'] as $permissionData){

                $currentRoutes = $permissionData['routes'] ?? [];
                $toAdd = array_diff($currentRoutes, $allRoutes);

                foreach ($toAdd as $addItem)
                {
                    Route::create($addItem);
                    $allRoutes[] = $addItem;
                }

                Permission::assignRoutes($permissionData['code'], $permissionData['routes'], $permissionData['name'] ?? null, $group->code);

                if(isset($permissionData['children'])){
                    Permission::addChildren($permissionData['name'], $permissionData['children'], true);
                }
            }
        }

    }

    /**
     * Guarda el grupo si no existe
     * @param $data
     * @return bool|AuthItemGroup
     */
    public function saveGroup($data)
    {
        if(!AuthItemGroup::find()->where(['name' => $data['name']])->exists()){
            $group = new AuthItemGroup();
            $group->load($data, '');
            if($group->save() === false){
                echo 'Error creating group '.$data['name'];
                return false;
            }
            return $group;
        }else{
            return AuthItemGroup::find()->where(['name' => $data['name']])->one();
        }
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        foreach ($this->permissions() as $groupData){
            foreach($groupData['permissions'] as $permissionData){
                Permission::removeChildren($permissionData['code'], $permissionData['routes']);
                if(isset($permissionData['children'])){
                    Permission::removeChildren($permissionData['code'], $permissionData['children']);
                }

                if(!(new Query())->select('*')->from('auth_item_child')->where(['parent' => $permissionData['code']])->exists()){
                    Permission::deleteIfExists(['name' => $permissionData['code']]);
                }
            }

            if(!AbstractItem::find()->where(['group_code' => $groupData['code']])->exists()){
                AuthItemGroup::deleteAll(['code' => $groupData['code']]);
            }
        }
    }

    /**
     * Deve devolver un array con los permisos a generar
     *
     *  [
     *      [
     *          name => group name,
     *          code => group code,
     *          permissions => [
     *              [
     *                  name => permission name,
     *                  code => permission code (permission system name or slug),
     *                  routes => [
     *                      example/create,
     *                      other-example/*
     *                  ],
     *                  children => [
     *                      other-permission-name,
     *                      and-other-permission-name
     *                  ]
     *              ],
     *              [
     *                  ...
     *              ]
     *          ]
     *      ]
     *  ]
     *
     * @return mixed
     */
    public abstract function permissions();
}