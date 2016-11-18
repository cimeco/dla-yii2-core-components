<?php
/**
 * Description of SearchStringHelper
 *
 * @author mmoyano
 */

namespace quoma\core\helpers;

use Yii;

class SearchStringHelper extends \quoma\core\helpers\StringHelper{
    
    public $string;
    
    public function getSearchWords($template = '{word}%'){
        
        $words = self::toArray($this->string);
        
        $filtered_words = self::filterWords($words, Yii::$app->params['exclude_from_search']);
        
        if(count($filtered_words) > 0){
            $words = $filtered_words;
        }
        
        foreach ($words as $i=>$word)
            $words[$i] = str_replace ('{word}', $word, $template);
        
        return $words;
        
    }
    
}
