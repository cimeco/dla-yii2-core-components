<?php
/**
 * Created by PhpStorm.
 * User: cgarcia
 * Date: 23/03/17
 * Time: 10:23
 */

namespace quoma\core\web;

use yii\filters\auth\HttpBasicAuth;
use yii\web\Response;
use Yii;

/**
 * REST Authentication
 * Controlador a heredar para el uso de REST.
 * Ya incorpora la seguridad via behavior, en caso de que no se requiera seguridad,
 * hay que colocar el parametro auth_api = false en el params.
 *
 * @author martin
 */
abstract class RestController extends \yii\rest\ActiveController{


    public $defaultMethods = ['login'];
    /**
     * Retorna los metodos posibles del controlador
     *
     * @return array
     */
    public abstract function getMethods();

    /**
     * Retorna los metodos que hay que deshabilitar por defecto.
     *
     * @return array
     */
    public abstract function getDisabledDefaultActions();

    public function actions()
    {
        $actions = parent::actions();
        $disabled_actions = $this->getDisabledDefaultActions();
        foreach ($disabled_actions as $key => $action ) {
            unset($actions[$action]);
        }

        return $actions;
    }

    public function behaviors()
    {
        $auth_api = (isset(\Yii::$app->params['auth_api']) ? \Yii::$app->params['auth_api'] : true );

        $behaviors = parent::behaviors();
        $behaviors =  [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => $this->getMethods(),  // in a controller
                // if in a module, use the following IDs for user actions
                // 'only' => ['user/view', 'user/index']
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
        ];

        if($auth_api) {
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
        }

        return $behaviors;
    }


    /**
     * Login para obtener auth_key para utilizar la api
     */
    public function actionLogin()
    {
        $model = new \webvimark\modules\UserManagement\models\forms\LoginForm();
        $model->username = Yii::$app->request->post('username');
        $model->password = Yii::$app->request->post('password');

        $model->validate();

        if ( !$model->hasErrors() )
        {
            //$identity = Yii::$app->user->identity;

            return [
                'status'    => 'success',
                'auth_key'  => $model->getUser()->auth_key,
                'username'  => $model->getUser()->username,
                'user_id'   => $model->getUser()->getId()
            ];
        }

        throw new \yii\web\HttpException(401, 'Not authorized.');

    }
}