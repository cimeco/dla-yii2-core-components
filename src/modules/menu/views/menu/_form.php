<?php
use yii\bootstrap\ActiveForm;

\quoma\core\assets\bootbox\BootboxAsset::register($this);
\yii\jui\JuiAsset::register($this);
?>

<style>
    .menu_item {

        min-height: 50px;
        margin-bottom: 5px;
        float: left;

    }

    .sub-menu{
        text-align: right;
        padding-bottom: 10px ;
        padding-top: 5px;

    }

    .sub-menu > .menu_item{
        position: relative;
        float: right;
        width: 97%;
        min-height: 0;
        border: none;
    }

    .menu-preview {
        list-style: none;
        padding-bottom: 15px;
        width: 100%;
    }
    .input-group-addon{
        border-bottom-left-radius: 0 0;
    }

    .dropdown-link {
        min-height: 100px;
        background-color: white;
    }


</style>

<div class="menu_form">

    <?php $form = ActiveForm::begin(['id' => 'menu-create-form']); ?>
    <div class="row no-margin-sides">

        <div class="col-lg-4 col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


                    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo Yii::t('app','Item Types')?></h4>
                        </div>
                        <div class="panel-body">

                            <div class="item_types" style="text-align: center">
                                <ul style="list-style: none">
                                    <?php foreach ($item_types as $type):?>
                                        <li>
                                            <?php echo \yii\bootstrap\Html::button('<span class="glyphicon glyphicon-plus"></span> '. Yii::t('app','Add'). ' '.$type['name'], [
                                                'class' => 'btn btn-default link_type',
                                                'data' => [
                                                    'view' => $type['view'],
                                                    'class' => $type['class'],
                                                    'parent' => '#'
                                                ],
                                                'style' => 'margin-bottom:20px; width:100%;'
                                            ])?>
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo \yii\helpers\Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary'])?>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-8 col-md-8">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="menu-preview sort-list">
                            <?php foreach ($model->children as $key => $item):?>
                                    <?php echo $this->render('_item', ['key' => $key, 'item' => $item, 'item_types' => $item_types])?>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php \yii\bootstrap\ActiveForm::end()?>

</div>

<script>

    var MenuForm= new function(){

        this.parent = undefined;
        this.init= function(){
            $(document).on('click', '.link_type', function(e){
                e.preventDefault();
                MenuForm.parent= $(this).data('parent');
                console.log(MenuForm.parent);
                $.ajax({
                    url: "<?php echo \yii\helpers\Url::to(['/menu/menu/item-form'])?>",
                    data: $.param({view: $(this).data('view')}),
                    dataType: 'json',
                    success: function(response){
                        if(response.status === 'success'){
                            bootbox.dialog({
                                title: "<?php echo Yii::t('app', 'Add Item')?>",
                                message: response.form,
                            })
                        }
                    }
                })
            })

            $('.menu-preview').on('click', '.btn-rmv', function(e){
                e.preventDefault();
                var item = $(this).closest('.menu_item');
                if($(this).closest('.menu_item').find('.sub-menu li').length > 0){
                    bootbox.confirm('<?php echo Yii::t('app','Are you sure to eliminate the item')?> ' + $(item).find('.item-label-input').val(), function(result){
                        if (result){
                            $(item).remove();
                        }
                    })
                }else{
                    $(item).remove();
                }
            })

            MenuForm.createSortables();
        }

        this.addItem= function(label, url, target, link_class, typeName, child){
            var position = MenuForm.parent === '#' ? $('.menu-preview li').length : $('#sub-'+ MenuForm.parent + ' li').length;
            console.log(position);

            var item= MenuForm.render(label, url, target, MenuForm.parent, position, link_class, typeName, child);

            if (MenuForm.parent === '#'){
                $('.menu-preview').append(item);
            }else{
                $('#sub-'+ MenuForm.parent).append(item);
            }
        }

        this.render= function(label, url, target, parent, position, link_class, typeName, child) {
            var pos= parent === '#' ? '['+ position + ']' : '['+parent+'][children]['+ position + ']';

            if(parent === '#' && child) {
                var item = '<li class="menu_item dropdown-link" id="i' + position + '" data-type_name="' + typeName + '" data-children=' + child + '> ';
            }else{
                var item = '<li class="menu_item" id="i' + position + '" data-type_name="' + typeName + '" data-children=' + child + '> ';

            }
            item = item + '<div class="input-group">'+
                              '<span class="input-group-addon"><span class="glyphicon glyphicon-move"></span></span>'+
                              '<span class="input-group-addon">'+typeName+'</span>';

            item= item + '<input type="input" name="Menu[items]'+pos+'[label]" class="form-control item-label-input" value="'+label+'">';

            item = item + '<span class="input-group-btn">';
            console.log(child);
            if(parent === '#' && child){

                item = item +'  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                '<span class="glyphicon glyphicon-plus"></span>'+
               '</button>'+
                '<ul class="dropdown-menu dropdown-menu-right">'+
                    <?php foreach ($item_types as $type):?>
                        <?php if ($type['child']):?>
                            '<li><a href="#" class="link_type" data-view="<?php echo $type['view']?>" data-parent="'+position+'" data-class="<?php echo addslashes($type['class'])?>"><?php echo Yii::t('app','Add'). ' '.$type['name']?></a></li>'+
                        <?php endif;?>
                    <?php endforeach;?>
                '</ul>';
            }
            item = item + ' <button type="button" class="btn btn-default btn-rmv"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>';


            item = item + '</span></div>';

            item= item + '<input type="hidden" name="Menu[items]'+pos+'[url]" class="item-url-input" value="'+url+'">'+
                    '<input type="hidden" name="Menu[items]'+pos+'[target]" class="item-target-input" value="'+target+'">'+
                    '<input type="hidden" name="Menu[items]'+pos+'[class]" class="item-class-input" value="'+link_class+'">';



            if(parent === '#' && child){
                item  = item + '<ul id="sub-'+position+'" class="sub-menu sort-list"></ul>';
            }

            item = item + '</li>';

            return item;
        }

        this.createSortables= function(){
            $('.menu-preview').sortable({
                dropOnEmpty: true,
                connectWith: '.sort-list',
                opacity: 0.5,
                update: function(event, ui){
                    MenuForm.refresh();
                    MenuForm.afterSort();
                    MenuForm.createSortables()
                }
            });

            $('.sub-menu').sortable({
                dropOnEmpty: true,
                connectWith: '.sort-list',
                opacity: 0.5,
                update: function(event, ui){
                    MenuForm.refresh();
                    MenuForm.afterSort();
                    MenuForm.createSortables()
                }
            });
        }

        this.afterSort= function(){
            $.each($('.menu-preview').children('.menu_item'), function(i, o){
                $(o).find('.item-label-input').attr('name', 'Menu[items]['+i+'][label]');
                $(o).find('.item-url-input').attr('name', 'Menu[items]['+i+'][url]');
                $(o).find('.item-target-input').attr('name', 'Menu[items]['+i+'][target]');
                $(o).find('.item-class-input').attr('name', 'Menu[items]['+i+'][class]');

                $.each($(o).find('.sub-menu').children('.menu_item'), function(j, sub){
                    $(sub).find('.item-label-input').attr('name', 'Menu[items]['+i+'][children]['+j+'][label]');
                    $(sub).find('.item-url-input').attr('name', 'Menu[items]['+i+'][children]['+j+'][url]');
                    $(sub).find('.item-target-input').attr('name', 'Menu[items]['+i+'][children]['+j+'][target]');
                    $(sub).find('.item-class-input').attr('name', 'Menu[items]['+i+'][children]['+j+'][class]');
                })

            })
        }

        this.refresh= function(){
            $.each($('.menu-preview').children('.menu_item'), function(i, o){
                var label = $(o).find('.item-label-input').val();
                var url = $(o).find('.item-url-input').val();
                var target = $(o).find('.item-target-input').val();
                var link_class = $(o).find('.item-class-input').val();
                var typeName= $(o).data('type_name');
                var has_children= $(o).data('children');
                var children= new Array();

                $.each($(o).find('.sub-menu').children('.menu_item'), function(j, sub){
                    var sub_label= $(sub).find('.item-label-input').val();
                    var sub_url= $(sub).find('.item-url-input').val();
                    var sub_target= $(sub).find('.item-target-input').val();
                    var sub_link_class= $(sub).find('.item-class-input').val();
                    var sub_typeName= $(sub).data('type_name');
                    var sub_has_children= $(sub).data('children');

                    children.push(MenuForm.render(sub_label, sub_url, sub_target, i, j, sub_link_class, sub_typeName, sub_has_children));
                });

                var new_item= MenuForm.render(label, url, target, '#', i, link_class, typeName, has_children);

                $(o).replaceWith(new_item);
                $.each(children, function (k, ch) {
                        $('#i'+ i).find('.sub-menu').append($(ch));
                });
            });
        }

    }



</script>

<?php $this->registerJs('MenuForm.init()')?>