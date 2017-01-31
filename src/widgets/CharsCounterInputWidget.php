<?php

namespace quoma\core\widgets;

use Yii;
use yii\bootstrap\InputWidget;
use yii\helpers\Html;

/**
 * Description of CharsCounterInputWidget
 *
 * @author martin
 */
class CharsCounterInputWidget extends InputWidget{
    
    public $containerOptions;
    
    public $addonOptions = ['class' => 'input-group-addon'];
    
    public $limit = 0;
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();
        echo Html::tag('div', $this->renderInput(), $this->containerOptions);
    }

    /**
     * Renders the input
     *
     * @return string
     */
    protected function renderInput()
    {
        Html::addCssClass($this->options, 'form-control');
        Html::addCssClass($this->containerOptions, 'bootstrap-timepicker input-group');
        
        $this->containerOptions['data-chars-count'] = 1;
        $this->containerOptions['data-limit'] = $this->limit;

        if($this->hasModel()){
            $attr = $this->attribute;
            $len = mb_strlen($this->model->$attr);
        }else{
            $len = mb_strlen($this->value);
        }
        
        $addOnContent = $this->limit ? $len.'/'.$this->limit : $len;
        
        $this->addonOptions['data-chars-counter'] = 1;
        
        $addon = Html::tag('span', $addOnContent, $this->addonOptions);
        return $this->getInput('textInput') . $addon;
    }
    
    
    /**
     * Generates an input.
     *
     * @param string $type the input type
     * @param boolean $list whether the input is of dropdown list type
     *
     * @return string the rendered input markup
     */
    protected function getInput($type)
    {
        if($this->limit > 0){
            $this->options['maxlength'] = $this->limit;
        }
        
        if ($this->hasModel()) {
            return Html::activeTextInput($this->model, $this->attribute, $this->options);
        }
        return Html::textInput($this->name, $this->value, $this->options);
    }
    
    /**
     * Registers the client assets for [[Timepicker]] widget
     */
    public function registerAssets()
    {
        $view = $this->getView();
        CharsCounterInputWidgetAssets::register($view);
        
        $view->registerJs('CharsCounter.init();');
    }
}
