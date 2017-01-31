<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace quoma\core\widgets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CharsCounterInputWidgetAssets extends AssetBundle
{
    public $sourcePath = __DIR__.'/chars-counter-assets';
    public $css = [
    ];
    public $js = [
        'js/CharsCounter.js',
    ];
    public $depends = [
        'yii\jui\JuiAsset'
    ];
}
