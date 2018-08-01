<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model quoma\core\modules\menu\models\Menu */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'Menu')]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->menu_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-update">

    <?php if (\quoma\core\modules\menu\MenuModule::getInstance()->show_view_title):?>
        <h1><?php echo $this->title?></h1>
    <?php endif;?>

    <?= $this->render('_form', [
        'model' => $model,
        'item_types' => $item_types
    ]) ?>

</div>
