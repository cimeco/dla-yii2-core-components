<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\helpers\UserA;

/* @var $this yii\web\View */
/* @var $searchModel quoma\core\modules\menu\models\search\MenuLocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Menu Locations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-location-index">

    <p>
        <?= UserA::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app','Create Menu Location'), ['create'], ['class' => 'btn btn-success']) ?>
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

            ['class' => 'common\components\grid\ActionColumn'],
        ],
    ]); ?>
</div>
