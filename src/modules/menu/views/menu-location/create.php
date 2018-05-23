<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model quoma\core\modules\menu\models\MenuLocation */

$this->title = Yii::t('app','Create Menu Location');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Menu Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-location-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
