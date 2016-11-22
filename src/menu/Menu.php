<?php
/**
 * Created by PhpStorm.
 * User: cgarcia
 * Date: 21/11/16
 * Time: 8:14
 */

namespace quoma\core\menu;


class Menu
{

    private $label;
    private $url;
    private $visible;
    private $childs = [];
    private $type;
    private $extra_data = null;
    private $is_root = false;

    const MENU_ITEM = 'item';
    const MENU_DIVIDER = 'divider';

    public function __construct($label="", $url=[], $visible=true, $type = Menu::MENU_ITEM, $extra_data = [], $is_root = false)
    {
        $this->label        = $label;
        $this->url          = $url;
        $this->visible      = $visible;
        $this->type         = $type;
        $this->extra_data   = $extra_data;
        $this->is_root      = $is_root;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return Menu
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return Menu
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     * @return Menu
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * @return array
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * @param array $childs
     * @return Menu
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;
        return $this;
    }

    public function hasChilds()
    {
        return count($this->childs) != 0;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Menu
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getExtraData()
    {
        return $this->extra_data;
    }

    /**
     * @param array|null $extra_data
     * @return Menu
     */
    public function setExtraData($extra_data)
    {
        $this->extra_data = $extra_data;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRoot()
    {
        return $this->is_root;
    }

    /**
     * @param boolean $is_root
     * @return Menu
     */
    public function setIsRoot($is_root)
    {
        $this->is_root = $is_root;
        return $this;
    }


    public function addChild(Menu $item)
    {
        $this->childs[] = $item;

        return $this;
    }

    public function removeChild($index)
    {
        if(array_key_exists($index, $this->childs)!==false) {
            unset($this->childs[$index]);
        }
        return $this;
    }

}