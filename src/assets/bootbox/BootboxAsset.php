<?php
namespace quoma\core\assets\bootbox;

use yii\web\AssetBundle;

class BootboxAsset extends AssetBundle
{
    public $sourcePath = '@vendor/quoma/yii2-core-components/src/assets/bootbox';
    //public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/bootbox.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'        
    ];
}
