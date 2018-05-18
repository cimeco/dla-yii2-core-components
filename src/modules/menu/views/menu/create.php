<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model quoma\core\modules\menu\models\Menu */

$this->title = 'Create Menu';
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?= $this->render('_form', [
        'model' => $model,
        'item_types' => $item_types
    ]) ?>

</div>
