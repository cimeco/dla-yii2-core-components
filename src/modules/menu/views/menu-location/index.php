<?php

use quoma\core\modules\menu\MenuModule;
use yii\helpers\Html;
use yii\grid\GridView;
use quoma\core\helpers\UserA;

/* @var $this yii\web\View */
/* @var $searchModel quoma\core\modules\menu\models\search\MenuLocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Menu Locations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-location-index">

    <?php if (\quoma\core\modules\menu\MenuModule::getInstance()->show_view_title):?>
        <h1><?php echo $this->title?></h1>
    <?php endif;?>

    <p>
        <?= UserA::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app','Create Menu Location'), ['create', 'site_id' => MenuModule::getInstance()->site_required  ? $this->context->website->website_id : null], ['class' => 'btn btn-success']) ?>
    </p>
    <hr/>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'description',
            'slug',
            [
                'attribute' => 'menu_id',
                'value' => function($model){
                    return (!empty($model->menu_id) ? $model->menu->name : '');
                }
            ],

            [
                'class' => 'quoma\core\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model){
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->menu_location_id, 'site_id' => MenuModule::getInstance()->site_required  ? $this->context->website->website_id : null], ['class' => 'btn btn-info']);
                    },
                    'update' => function($url, $model){
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->menu_location_id, 'site_id' => MenuModule::getInstance()->site_required  ? $this->context->website->website_id : null], ['class' => 'btn btn-primary']);
                    },
                    'delete' => function($url, $model){
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->menu_location_id, 'site_id' => MenuModule::getInstance()->site_required ? $this->context->website->website_id : null], ['class' => 'btn btn-danger']);
                    },
                ]
            ],

        ],
    ]); ?>
</div>
