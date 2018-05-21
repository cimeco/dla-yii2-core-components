<?php

?>

<div class="_absolute_link_form">
    <div id="msj"></div>
    <form>
        <div class="form-group" id="abs-link-group-label">
            <label id="abs-label-label" class="form-label" for="item_label"><?=  Yii::t('app', 'Label')?> :</label>
            <input type="text" class="form-control" id="item_label" aria-describedby='abs-label-help'>
            <span class="help-block" id='abs-label-help' style="display: none;"><?= Yii::t('app', 'Label can`t be empty')?></span>
        </div>

        <div class="form-group" id="abs-link-group-url">
            <label id="abs-url-label" class="form-label" for="item_url"><?=  Yii::t('app', 'Url')?> :</label>
            <input type="url" class="form-control" id="item_url" aria-describedby='abs-url-help'>
            <span class="help-block" id='abs-url-help' style="display: none;"><?= Yii::t('app', 'Url can`t be empty')?></span>
            <span class="help-block" id='abs-url-help2' style="display: none;"><?= Yii::t('app', 'Url is invalid')?></span>
        </div>
        <div class="form-group" id="abs-link-group-target">
            <label class="form-label" for="abs-target"><?=  Yii::t('app', 'Open in')?> :</label>
            <input type="radio" name="item_target "value="_self" checked><?php echo Yii::t('app', 'Same Page')?>
            <input type="radio" name="item_target "value="_blank"><?php echo Yii::t('app', 'New Tab')?>
        </div>
    </form>

    <?php echo \yii\helpers\Html::button('<span class="glyphicon glyphicon-plus"></span>'. Yii::t('app','Add'), ['class' => 'btn btn-primary', 'id' => 'add-abs-btn'])?>
</div>

<script>

    var AbsoluteForm= new function(){
        this.init= function(){
            alert('Absolute init triggered');
            $('#add-abs-btn').off('click').on('click', function(e){
                e.preventDefault();
                alert('click triggered');
                $('#msj').empty();

                if($('#item_label').val() === ''){
                    $('#msj').append('<div class="alert alert-danger "><?php echo Yii::t('app','Label cant be empty')?></div>')
                    return false;
                }
                if($('#item_url').val() === ''){
                    $('#msj').append('<div class="alert alert-danger" ><?php  echo Yii::t('app','Url cant be empty')?>"</div>')
                    return false;
                }
                MenuForm.addItem($('#item_label').val(), $('#item_url').val(), $('item_target').val(), "<?php echo addslashes(\quoma\core\modules\menu\components\defaultitems\absolute\AbsoluteLink::className())?>", "<?php echo \quoma\core\modules\menu\components\defaultitems\absolute\AbsoluteLink::typeName()?>", false);

                bootbox.hideAll();
            })
        }
    }

</script>

<?php $this->registerJs('AbsoluteForm.init()')?>