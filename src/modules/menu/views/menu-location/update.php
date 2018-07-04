<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model quoma\core\modules\menu\models\MenuLocation */

$this->title = Yii::t('app','Update Menu Location'). ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' =>  Yii::t('app','Menu Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->menu_location_id]];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="menu-location-update">

    <?php if (\quoma\core\modules\menu\MenuModule::getInstance()->show_view_title):?>
        <h1><?php echo $this->title?></h1>
    <?php endif;?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
