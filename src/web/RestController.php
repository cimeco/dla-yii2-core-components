<?php
/**
 * Created by PhpStorm.
 * User: cgarcia
 * Date: 23/03/17
 * Time: 10:23
 */

namespace quoma\core\web;

use yii\filters\auth\HttpBasicAuth;

/**
 * REST Authentication
 *
 * @author martin
 */
class RestController extends \yii\rest\ActiveController{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'except' => ['login'],
            'class' => HttpBasicAuth::className(),
            'auth' => function ($username, $password) {
                // Return Identity object or null
                $user = \webvimark\modules\UserManagement\models\User::findOne([
                    'username' => $username,
                ]);

                if($user && $user->validatePassword($password)){
                    return $user;
                }

                return null;
            },

        ];
        return $behaviors;
    }
}