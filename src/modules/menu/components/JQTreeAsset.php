<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\modules\menu\components;

/**
 * Description of JQTreeAsset
 *
 * @author juan
 */
class JQTreeAsset extends \yii\web\AssetBundle{
    
    public $sourcePath = '@backend/web/js/vakata-jstree';
    public $css = [
        'dist/themes/default/style.min.css'
    ];
    public $js = [
        'dist/jstree.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
    ];
}
