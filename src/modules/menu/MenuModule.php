<?php

namespace quoma\core\modules\menu;

use quoma\core\menu\Menu;
use quoma\core\module\QuomaModule;
/**
 * menu module definition class
 */
class MenuModule extends QuomaModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'quoma\core\modules\menu\controllers';

    public $defaultItemsPath = ['@menu/components/defaultitems'];

    public $itemsPaths= [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @return Menu
     */
    public function getMenu(Menu $menu)
    {
        // TODO: Implement getMenu() method.
    }

    /**
     * Retorna un arreglo con los nombres de los modulos de los que se depende.
     *
     * @return array
     */
    public function getDependencies()
    {
        return [];
    }
}
