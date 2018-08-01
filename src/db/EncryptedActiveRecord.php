<?php
declare(strict_types=1);
namespace quoma\core\db;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

/**
 * Description of EncryptedActiveRecord
 *
 * Para almacenar datos encriptados.
 * Encripta y desencripta datos de forma automatica.
 * 
 * Los atributos a encriptar deben ser devueltos por el método getEnctyptedAttributes:
 * 
 *  return [
 *      'attr_1',
 *      'attr_2',
 *      ...
 *  ]
 * 
 * El key debe ser generado utilizando:
 *  Defuse\Crypto\Key::createNewRandomKey();
 * 
 * IMPORTANTE:
 * https://stackoverflow.com/questions/16600708/how-do-you-encrypt-and-decrypt-a-php-string#answer-30159120
 * 
 * @author mmoyano
 */
class EncryptedActiveRecord extends ActiveRecord{
    
    private $_key;
    
    public $decryptFailureException = true;
    
    public function setKey($key)
    {
        $this->_key = Key::loadFromAsciiSafeString($key);
    }
    
    public function getEncryptedAttributes(): array
    {
        return [];
    }
    
    public function __get($name) {
        
        if(in_array($name, $this->getEncryptedAttributes())){
            $value = parent::__get($name);
            
            if(!empty($value)){
                return $this->decrypt($value);
            }
        }
        
        return parent::__get($name);
    }
    
    public function __set($name, $value) {
        
        if(in_array($name, $this->getEncryptedAttributes())){
            if(!empty($value)){            
                $value = $this->encrypt($value);
            }
        }
        
        parent::__set($name, $value);
    }
    
    protected function encrypt(string $value): string
    {
        return Crypto::encrypt($value, $this->_key);
    }
    
    protected function decrypt(string $value): string
    {
        try{
            return Crypto::decrypt($value, $this->_key);
        }catch(\Exception $e){
            \Yii::$app->session->setFlash('error', 'Decrypt failure: '.$e->getMessage());
        }
    }
    
}
