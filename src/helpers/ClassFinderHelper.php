<?php
/**
 * Created by PhpStorm.
 * User: cgarcia
 * Date: 29/12/15
 * Time: 15:29
 */

namespace quoma\core\helpers;


use ReflectionClass;
use Yii;

class ClassFinderHelper
{
    /**
     * Busca los nombres de las clases alojadas en los directorios
     * de la configuracion pasada como parametro.
     *
     * @param $config   Listado de directorios
     * @return array
     */
    public static function findClasses($config, $excludeAbstract=true, $excludeInterface=true, $extends = [], $implements = [])
    {
        $extends = is_null($extends) ? [] : $extends;
        $implements = is_null($implements) ? [] : $implements;

        $classes = get_declared_classes();
        // Itero en los directorios configuados
        foreach ($config as $key=>$dir) {
            // Obtengo todos los archivos
            foreach(Yii::$app->modules as $key=>$value)
                Yii::$app->getModule($key);

            $fullDir = Yii::getAlias($dir);
            $files = scandir($fullDir);
            // Itero los archivos buscando clases
            foreach ($files as $key2=>$file) {
                if ($file!="." && $file!=".." && !is_dir($fullDir .'/'. $file)) {
                    include_once $fullDir .'/'. $file;
                }
            }
        }
        $classes = array_diff( get_declared_classes(), $classes);

        $retClasses = [];
        foreach ($classes as $clase) {
            $class = new ReflectionClass($clase);
            if( ($class->isAbstract() && !$excludeAbstract) || ($class->isInterface() && !$excludeInterface) || ($excludeInterface && $excludeAbstract) ) {
                // Verifico que extienda alguna de las clases pasadas por parametros
                $extendsImplements = (count($extends)==0 && count($implements)==0);
                foreach($extends as $value) {
                    if($class->isSubclassOf($value)) {
                        $extendsImplements = true;
                        break;
                    }
                }
                foreach($implements as $value) {
                    if($class->implementsInterface($value)) {
                        $extendsImplements = true;
                        break;
                    }
                }
                if( $extendsImplements ) {
                    $retClasses[] = $clase;
                }
            }
        }
        return $retClasses;
    }
}