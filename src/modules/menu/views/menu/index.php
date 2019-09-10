<?php

use quoma\core\modules\menu\MenuModule;
use yii\helpers\Html;
use yii\grid\GridView;
use quoma\core\helpers\UserA;

/* @var $this yii\web\View */
/* @var $searchModel quoma\core\modules\menu\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <?php if (MenuModule::getInstance()->show_view_title):?>
        <h1><?php echo $this->title?></h1>
    <?php endif;?>


    <p>
        <?= UserA::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app','Create Menu'), ['create', 'site_id' => MenuModule::getInstance()->site_required ? $this->context->website->website_id : null ], ['class' => 'btn btn-success']) ?>
    </p>
    <hr/>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'slug',
            'description',

            [
                'class' => 'quoma\core\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model){
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->menu_id, 'site_id' => MenuModule::getInstance()->site_required  ? $this->context->website->website_id : null], ['class' => 'btn btn-info']);
                    },
                    'update' => function($url, $model){
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->menu_id, 'site_id' => MenuModule::getInstance()->site_required  ? $this->context->website->website_id : null], ['class' => 'btn btn-primary']);
                    },
                    'delete' => function ($url, $model, $key) {
                        if(!isset($model->deletable) || $model->deletable){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                                'class' => 'btn btn-danger',
                            ]);
                        }else{
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['class' => 'btn btn-danger disabled']);
                        }
                    }
                ]
            ],
        ],
    ]); ?>
</div>
