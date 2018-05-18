<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use quoma\core\helpers\UserA;

/* @var $this yii\web\View */
/* @var $model common\modules\menu\models\MenuLocation */

$this->title = Yii::t('app','Menu Location') . ': '. $model->name;
$this->params['breadcrumbs'][] = ['label' =>  Yii::t('app','Menu Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-location-view">

    <p>
        <?= UserA::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app','Update'), ['update', 'id' => $model->menu_location_id], ['class' => 'btn btn-primary']) ?>
        <?= UserA::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app','Delete'), ['delete', 'id' => $model->menu_location_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app','Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <hr/>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
            'slug',
            [
              'label' => Yii::t('app', 'Associated Menu'),
              'value' => (!empty($model->menu_id) ? $model->menu->name : '')  
            ],
        ],
    ]) ?>

</div>
