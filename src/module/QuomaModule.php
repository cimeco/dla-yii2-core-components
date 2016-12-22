<?php
/**
 * Created by PhpStorm.
 * User: cgarcia
 * Date: 18/11/16
 * Time: 10:27
 */

namespace quoma\core\module;

use quoma\core\menu\Menu;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

/**
 * Class QuomaModule
 * Clase abstracta para extender los modulos de Quoma, simplifica lo siguiente:
 *  - Carga de traducciones del modulo.
 *  - Uso de traducciones en el modulo.
 *  - Carga de parametros por defecto. ('params.php')
 *  - Crea un alias del modulo.
 *
 * @package quoma\core\module
 */
abstract class QuomaModule extends Module implements BootstrapInterface
{
    protected $namespace = '';

    public function init()
    {
        parent::init();
        // Creo un alias con el id unico, para mas facil acceso.
        Yii::setAlias("@".$this->getUniqueId(), $this->basePath);

        // Creo un alias con el namespace, para poder usarlo con cosas internas de Yii (commands)
        $class = get_called_class();
        $this->namespace = str_replace('\\', '/', substr($class, 0, strrpos($class, '\\')));
        Yii::setAlias("@".$this->namespace , $this->basePath);

        foreach($this->getDependencies() as $dependency) {
            if(Yii::$app->hasModule($dependency)) {
                Yii::$app->getModule($dependency);
            }
        }
    }

    /**
     * Registra las traducciones para el modulo.
     */
    public function registerTranslations() {
        \Yii::$app->i18n->translations[$this->getUniqueId()] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => Yii::getAlias("@".$this->getUniqueId()) . '/messages',
        ];
    }

    /**
     * Traducciones para el modulo.
     *
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($message, $params = [], $language = null, $category='' ) {
        return \Yii::t((empty($category) ? self::getInstance()->getUniqueId() : $category ), $message, $params, $language);
    }

    /**
     * Carga los parametros por defecto del modulo, y de existir, carga los parametros
     * particulares para la aplicacion. Los particulares, se deben guardar en
     * config/ y el nombre seria params-(id del modulo).php
     */
    public function loadParams()
    {
        $dir = dirname((new \ReflectionClass($this))->getFileName());
        if(file_exists($dir . '/params.php')){
            $this->params = require($dir . '/params.php');
        }
        $externalConfig = Yii::getAlias('@app')."/config/params-".$this->getUniqueId().".php";
        if(file_exists($externalConfig)) {
            $this->params = array_replace_recursive($this->params, require ($externalConfig) );
        }

    }

    /**
     * Se ejecuta siempre y pone el namespace para buscar los comandos por defecto.
     *
     * @param \yii\base\Application $app
     */
    public function bootstrap($app) {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = str_replace("/", "\\", $this->namespace ). '\commands';
        }
    }


    /**
     * @return Menu
     */
    public abstract function getMenu(Menu $menu);

    /**
     * Retorna un arreglo con los nombres de los modulos de los que se depende.
     *
     * @return array
     */
    public abstract function getDependencies();
}