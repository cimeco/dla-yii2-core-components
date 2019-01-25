<?php

namespace quoma\core\helpers;

use Yii;
use yii\helpers\FormatConverter;

/**
 * Description of DateFormatter
 *
 * @author mmoyano
 */
class DateFormatter extends \yii\base\Component{
    
    public $dateFormat;
    public $datetimeFormat;
    
    public $dbDateFormat = 'php:Y-m-d';
    public $dbDatetimeFormat = 'php:Y-m-d H:i:s';
    
    private static $_instance;
    /**
     * @return DateFormatter
     */
    public static function getInstance()
    {
        if(!self::$_instance){
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    public function init() {
        parent::init();
        
        $this->dateFormat = Yii::$app->formatter->dateFormat;
        $this->datetimeFormat = Yii::$app->formatter->datetimeFormat;
        
    }
    
    public function format($from, $to, $date)
    {
        $from = $this->normalizeFormat($from);
        $to = $this->normalizeFormat($to);
        
        $date = \DateTime::createFromFormat($from, $date);

        return $date->format($to);
    }
    
    public function dbDateFormat($date)
    {
        if(empty($date)){
            return null;
        }
        
        return $this->format($this->dateFormat, $this->dbDateFormat, $date);

    }
    
    public function hDateFormat($date)
    {
        if(empty($date)){
            return null;
        }
        
        return $this->format($this->dbDateFormat, $this->dateFormat, $date);
        
    }
    
    public function dbDatetimeFormat($date)
    {
        if(empty($date)){
            return null;
        }
        
        return $this->format($this->datetimeFormat, $this->dbDatetimeFormat, $date);

    }
    
    public function hDatetimeFormat($date)
    {
        if(empty($date)){
            return null;
        }
        
        return $this->format($this->dbDatetimeFormat, $this->datetimeFormat, $date);
        
    }
    
    public function normalizeFormat($format)
    {

        if (strncmp($format, 'php:', 4) === 0) {
            $format = substr($format, 4);
        }else {
            $format = FormatConverter::convertDateIcuToPhp($format, 'date');
        }
        
        return $format;
    }
    
}
