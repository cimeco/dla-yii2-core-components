<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model quoma\core\modules\menu\models\MenuLocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-location-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'menu_id')->widget(\kartik\select2\Select2::className(), [
        'data' => yii\helpers\ArrayHelper::map(quoma\core\modules\menu\models\Menu::find()->all(), 'menu_id', 'name'),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => Yii::t('app', 'Select an option...')],
        'pluginOptions' => ['allowClear' => true]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
