<?php

namespace quoma\core\db;

use Yii;
use yii\base\Component;

/**
 * Description of DynamicDb
 *
 * @author mmoyano
 */
class DynamicDb extends Component{
    
    public $dbname;
    public $class;
    public $host;
    public $username;
    public $password;
    public $port;
    public $charset;
    
    private $_db;
    
    public function init() {
        parent::init();
        
        if(!$this->class){
            $this->class = \yii\db\Connection::className();
        }
    }
    
    public function setDb(string $dbname, array $config = [])
    {
        $this->_db = array_merge([
            'class' => $this->class,
            'dsn' => "mysql:host=$this->host:$this->port;dbname=$dbname",
            'username' => $this->username,
            'password' => $this->password,
            'charset' => $this->charset,
            'password' => $this->password,
        ],$config);
    }
    
    public function getDb()
    {
        return Yii::createObject($this->_db);
    }
    
}
