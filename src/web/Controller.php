<?php

namespace quoma\core\web;

use yii\filters\VerbFilter;

/**
 * Description of Controller
 *
 * @author mmoyano
 */
class Controller extends \yii\web\Controller{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }
    
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            \app\models\Log::log();
            return true;
        }
        return false;
    }
    
}
