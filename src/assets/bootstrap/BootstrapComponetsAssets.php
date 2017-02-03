<?php

namespace quoma\core\assets\bootstrap;

use Yii;
use yii\base\Component;

/**
 * Description of BootstrapComponetsAssets
 *
 * @author mmoyano
 */
class BootstrapComponetsAssets extends yii\web\AssetBundle{
    
    public $sourcePath = __DIR__.DIRECTORY_SEPARATOR.'source';
    
    public $css = [
        'css/bootstrap-components.css'
    ];
}
