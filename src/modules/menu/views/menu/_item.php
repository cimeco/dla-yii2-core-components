<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 16/05/18
 * Time: 14:56
 */
?>

<li class="menu_item" data-type_name="<?php echo $item->classLabel?>" data-children=<?php echo $item->children ? 1 : 0?>>


    <div class="input-group">
        <span class="input-group-addon"><span class="glyphicon glyphicon-move"></span></span>
        <span class="input-group-addon"><?php echo $item->classLabel?></span>
        <?php echo \yii\bootstrap\Html::textInput('Menu[items]['.$key.'][label]', $item->label, ['class' => 'form-control item-label-input' ])?>
        <span class="input-group-btn">
            <?php if($item->children):?>
                <button type="button" class="btn btn-default   dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <?php foreach ($item_types as $type):?>
                        <?php if ($type['child']):?>
                            <li><a href="#" class="link_type" data-view="<?php echo $type['view']?>" data-parent="<?php echo $key?>" data-class="<?php echo addslashes($type['class'])?>"><?php echo Yii::t('app','Add'). ' '.$type['name']?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
             <?php endif;?>
            <button type="button" class="btn btn-default btn-rmv"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
        </span>
    </div>


    <input type="hidden" name="Menu[items][<?php echo $key?>][url]" class="item-url-input" value="<?php echo $item->url?>">
    <input type="hidden" name="Menu[items][<?php echo $key?>][target]" class="item-target-input" value="<?php echo $item->target?>">
    <input type="hidden" name="Menu[items][<?php echo $key?>][class]" class="item-class-input" value="<?php echo $item->class?>">

    <ul class="sub-menu sort-list">
        <?php foreach ($item->children as $j => $child):?>
            <li class="menu_item" data-type_name="<?php echo $child->classLabel?>" data-children=0>
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-move"></span></span>
                    <span class="input-group-addon"><?php echo $child->classLabel?></span>
                    <?php echo \yii\bootstrap\Html::textInput('Menu[items]['.$key.'][children]['.$j.'][label]', $child->label, ['class' => 'form-control item-label-input' ])?>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-rmv"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                        </span>
                </div>


                <input type="hidden" name="Menu[items][<?php echo $key?>][children][<?php echo $j ?>][url]" class="item-url-input" value="<?php echo $child->url?>">
                <input type="hidden" name="Menu[items][<?php echo $key?>][children][<?php echo $j ?>][target]" class="item-target-input" value="<?php echo $child->target?>">
                <input type="hidden" name="Menu[items][<?php echo $key?>][children][<?php echo $j ?>][class]" class="item-class-input" value="<?php echo $child->class?>">
            </li>
        <?php endforeach;?>
    </ul>

</li>