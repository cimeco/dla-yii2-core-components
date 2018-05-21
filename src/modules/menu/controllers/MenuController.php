<?php

namespace quoma\core\modules\menu\controllers;

use Yii;
use quoma\core\modules\menu\models\Menu;
use quoma\core\modules\menu\models\search\MenuSearch;
use quoma\core\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            
        ]);
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->menu_id]);
        } else {
            $item_types= \quoma\core\modules\menu\models\MenuItem::getTypes();
            return $this->render('create', [
                'model' => $model,
                'item_types' => $item_types,
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Debido a que no siempre se modifican los atributos del modelo en cuestion, el evento afterSave no se dispara siempre al actualizar
            // y existe la posibilidad de que los cambios en los items no se salven
            // Para saber si se salvaron los items uso el atributo _saveItems, que vendra en true si se disparo el afterSave,
            //  de lo contrario hay que salvar los items
            if (!$model->_saveItems) {
                $model->saveItems();
            }
            return $this->redirect(['view', 'id' => $model->menu_id]);
        } else {
            $item_types= \quoma\core\modules\menu\models\MenuItem::getTypes();
            return $this->render('update', [
                'model' => $model,
                'item_types' => $item_types,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Devuelve por ajax el formulario de item solicitado por view
     * @param type $view
     * @return type
     */
    public function actionItemForm($view){
        if (\Yii::$app->request->isAjax) {
            $form = $this->renderAjax($view);
            
            \Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;
            
            return ['status'=> 'success', 'form' => $form];
        }
    }

    
    public function actionClone($id){        
        
        $clone= new Menu();
        
        if ($clone->load(Yii::$app->request->post()) && $clone->save()) {
            return $this->redirect(['view', 'id' => $clone->menu_id]);
        }else{
            $originMenu= $this->findModel($id);
            $item_types= \common\modules\menu\models\MenuItem::getTypes();
            return $this->render('clone', ['clone'=> $clone, 'origin' => $originMenu, 'item_types' => $item_types]);
        }
        
    }
}
