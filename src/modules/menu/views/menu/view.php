<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\helpers\UserA;

/* @var $this yii\web\View */
/* @var $model quoma\core\modules\menu\models\Menu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="menu-view menu_form">

    <p>
        <?= UserA::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Update'), ['update', 'id' => $model->menu_id], ['class' => 'btn btn-primary']) ?>
        <?= UserA::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), ['delete', 'id' => $model->menu_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <span class="btn-separator"></span>
        <?= UserA::a('<span class="glyphicon glyphicon-export"></span> '.Yii::t('app', 'Clone'), ['clone', 'id' => $model->menu_id], ['class' => 'btn btn-warning']) ?>
    </p>
    <hr/>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'menu_id',
            'name',
            'slug',
            'description',
        ],
    ]) ?>
    <h3><?php echo Yii::t('app', 'Preview')?></h3>
    <hr>
   <div class="row">

        <div class="col-lg-1 col-lg-offset-5">
            <ul style="list-style: disc !important;">
                <?php foreach ($model->children as $item):?>
                    <li style="list-style: disc !important;">
                        <h4 data-toggle="tooltip" data-placement="bottom" data-title="<?php echo $item->createUrl() ?>"><?php echo $item->label?></h4>
                        <ul style="margin-left: 25px">
                            <?php foreach ($item->children as $child):?>
                                <li data-toggle="tooltip" data-placement="bottom" data-title="<?php echo $child->createUrl() ?>" style="list-style: disc !important;"><h4><?php echo $child->label?></h4></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>

    </div>

</div>


<script>
    
    var MenuView= new function(){
        this.init= function () {
            $('[data-toggle="tooltip"]').tooltip();
        }
    }
    
    
    
</script>

<?php $this->registerJs('MenuView.init()')?>