<?php

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

    <p>
        <?= UserA::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app','Create Menu'), ['create'], ['class' => 'btn btn-success']) ?>
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

            ['class' => 'quoma\core\grid\ActionColumn'],
        ],
    ]); ?>
</div>
