<?php
/**
 * Created by PhpStorm.
 * User: cgarcia
 * Date: 21/11/16
 * Time: 8:32
 */

namespace quoma\core\menu;

/**
 * Interface MenuRenderInterface
 * @package quoma\core\menu
 */
interface MenuRenderInterface
{
    public function render(Menu $menu);
}