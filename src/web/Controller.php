<?php

namespace quoma\core\web;

use yii\filters\VerbFilter;
use quoma\modules\log\models\Log;

/**
 * Description of Controller
 *
 * @author mmoyano
 */
class Controller extends \yii\web\Controller
{
    public $noLogRoutes = ['site/login', 'log/log/index', 'log/log/view'];

    /**
     * IMPORTANT !!!
     * Overwrite this method in this way:
     *
     * public function behaviors() {
     *     return array_merge([
     *         'verbs' => [
     *             'class' => VerbFilter::className(),
     *             'actions' => [
     *                 'delete' => ['post'],
     *              ],
     *         ],
     *     ], parent::behaviors() );
     * }
     *
     * @return array
     */
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
    
    public function beforeAction($action) 
    {
        if (parent::beforeAction($action)) {
            if(\Yii::$app->hasModule('log') && !in_array($this->getRoute(), $this->noLogRoutes)) {
                Log::log();
            }
            return true;
        }
        return false;
    }
    
}
