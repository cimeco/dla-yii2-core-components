<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model quoma\core\modules\menu\models\Menu */

$this->title = 'Clone Menu: '. $origin->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-clone">

    <?= $this->render('_form', [
        'model' => $origin,
        'item_types' => $item_types,
        'origin' => $origin    
    ]) ?>

</div>
