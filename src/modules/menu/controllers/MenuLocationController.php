<?php

namespace quoma\core\modules\menu\controllers;

use quoma\core\modules\menu\MenuModule;
use Yii;
use quoma\core\modules\menu\models\MenuLocation;
use quoma\core\modules\menu\models\search\MenuLocationSearch;
use quoma\core\modules\menu\components\Controller as ModuleController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for MenuLocation model.
 */
class MenuLocationController extends ModuleController
{


    /**
     * Lists all MenuLocation models.
     * @return mixed
     */
    public function actionIndex($site_id= null)
    {
        if (MenuModule::getInstance()->multisite && MenuModule::getInstance()->site_required && empty($site_id)){
            throw new BadRequestHttpException('site_id is required');
        }

        if (MenuModule::getInstance()->multisite){
            $this->setWebsite($site_id);
        }

        $searchModel = new MenuLocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MenuLocation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $site_id= null )
    {
        if (MenuModule::getInstance()->multisite && MenuModule::getInstance()->site_required && empty($site_id)){
            throw new BadRequestHttpException('site_id is required');
        }

        if (MenuModule::getInstance()->multisite){
            $this->setWebsite($site_id);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MenuLocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($site_id= null)
    {
        if (MenuModule::getInstance()->multisite && MenuModule::getInstance()->site_required && empty($site_id)){
            throw new BadRequestHttpException('site_id is required');
        }

        if (MenuModule::getInstance()->multisite){
            $this->setWebsite($site_id);
        }

        $model = new MenuLocation();
        $model->site_id= $site_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->menu_location_id, 'site_id' => $site_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MenuLocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $site_id= null )
    {
        if (MenuModule::getInstance()->multisite && MenuModule::getInstance()->site_required && empty($site_id)){
            throw new BadRequestHttpException('site_id is required');
        }

        if (MenuModule::getInstance()->multisite){
            $this->setWebsite($site_id);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->menu_location_id, 'site_id' => $site_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MenuLocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $site_id)
    {
        if (MenuModule::getInstance()->multisite && MenuModule::getInstance()->site_required && empty($site_id)){
            throw new BadRequestHttpException('site_id is required');
        }

        if (MenuModule::getInstance()->multisite){
            $this->setWebsite($site_id);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'site_id' => $site_id]);
    }

    /**
     * Finds the MenuLocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuLocation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
