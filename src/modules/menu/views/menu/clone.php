<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model quoma\core\modules\menu\models\Menu */

$this->title = 'Clone Menu: '. $origin->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-clone">
    <?php if (\quoma\core\modules\menu\MenuModule::getInstance()->show_view_title):?>
        <h1><?php echo $this->title?></h1>
    <?php endif;?>
    <?= $this->render('_form', [
        'model' => $origin,
        'item_types' => $item_types,
        'origin' => $origin    
    ]) ?>

</div>
