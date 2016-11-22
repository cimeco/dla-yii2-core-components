<?php
/**
 * Created by PhpStorm.
 * User: cgarcia
 * Date: 21/11/16
 * Time: 16:22
 */

namespace quoma\core\menu\impl;

use quoma\core\menu\Menu;
use quoma\core\menu\MenuRenderInterface;

/**
 * Class NavMenuRender
 * Clase responsable de crear los items que tiene que renderizar el NavMenu.
 *
 * @package quoma\core\menu\impl
 */
class NavMenuRender implements  MenuRenderInterface
{

    /**
     * Crea el array de menu para renderizar.
     *
     * @param mixed $menu
     * @return array
     */
    public function render($menu)
    {
        $main = [];
        if(is_array($menu)) {
            /** @var Menu $menu */
            foreach( $menu as $menuItem ) {
                if($menuItem->hasChilds()) {
                    $items = $this->render($menuItem);
                }
                $item = $this->createItem($menuItem);
                if(!empty($items)) {
                    $item['items'] = $items;
                }
                $main[] = $item;
            }
        } else {
            if($menu->hasChilds()){
                foreach ($menu->getChilds() as $child) {
                    $items[] = $this->render($child);
                }
            }

            if(!$menu->isRoot()) {
                $main = $this->createItem($menu);
                if(!empty($items)) {
                    $main['items'] = $items;
                }
            } else {
                $main[] = $this->createItem($menu);
                if(!empty($items)) {
                    $main[count($main)]['items'] = $items;
                }
            }
        }
        return $main;
    }

    /**
     * Crea cada item del menu.
     *
     * @param Menu $menu
     * @return array
     */
    private function createItem(Menu $menu)
    {
        $result['label'] = $menu->getLabel();
        $result['visible'] = $menu->getVisible();
        if(!empty($menu->getUrl())) {
            $result['url'] = $menu->getUrl();
        }
        if(!empty($menu->getExtraData())) {
            $result = array_merge($result,  $menu->getExtraData());
        }
        return $result;
    }
}