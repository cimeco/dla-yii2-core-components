<?php

?>

<div class="_dropdown_link_form">
    <div id="msj"></div>
    <form>
        <div class="form-group" id="abs-link-group-label">
            <label id="abs-label-label" class="form-label" for="item_label"><?=  Yii::t('app', 'Label')?> :</label>
            <input type="text" class="form-control" id="item_label" aria-describedby='abs-label-help'>
            <span class="help-block" id='abs-label-help' style="display: none;"><?= Yii::t('app', 'Label can`t be empty')?></span>
        </div>

    </form>

    <?php echo \yii\helpers\Html::button('<span class="glyphicon glyphicon-plus"></span>'. Yii::t('app','Add'), ['class' => 'btn btn-primary', 'id' => 'add-drop-btn'])?>
</div>

<script>

    var DropDownForm= new function(){
        this.init= function(){
            $('#add-drop-btn').off('click').on('click', function(e){
                e.preventDefault();

                $('#msj').empty();

                if($('#item_label').val() === ''){
                    $('#msj').append('<div class="alert alert-danger "><?php echo Yii::t('app','Label cant be empty')?></div>')
                    return false;
                }

                MenuForm.addItem($('#item_label').val(), '#', undefined, "<?php echo addslashes(\common\modules\menu\models\items\DropdownLink::className())?>", "<?php echo \common\modules\menu\models\items\DropdownLink::typeName()?>", 1);

                bootbox.hideAll();
            })
        }
    }

</script>

<?php $this->registerJs('DropDownForm.init()')?>